<?php

namespace App\Http\Controllers;

use App\Mail\ComplaintReplyMail;
use App\Mail\ComplaintStatusUpdatedMail;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ComplaintController extends Controller
{
    /**
     * Internal dashboard: list every complaint submitted, with filters.
     */
    public function index(Request $request): View
    {
        $complaints = Complaint::with(['client', 'assignee'])
            ->status($request->get('status'))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->get('priority')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($sub) use ($term) {
                    $sub->where('ticket_number', 'like', "%{$term}%")
                        ->orWhere('subject', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint): View
    {
        $complaint->load(['client', 'assignee', 'replies.user']);
        $staff = User::orderBy('name')->get(['id', 'name']);

        return view('complaints.show', compact('complaint', 'staff'));
    }

    /**
     * Update status / priority / assignment from the internal view.
     * Emails the client whenever the status actually changes.
     */
    public function update(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $previousStatus = $complaint->status;

        $data['resolved_at'] = $data['status'] === 'resolved' ? now() : $complaint->resolved_at;

        $complaint->update($data);

        if ($previousStatus !== $complaint->status) {
            Mail::to($complaint->email)->send(new ComplaintStatusUpdatedMail($complaint, $previousStatus));
        }

        return redirect()
            ->route('complaints.show', $complaint)
            ->with('success', 'Complaint updated successfully.');
    }

    /**
     * Staff posting a reply (visible to client) or an internal note.
     * Only non-internal replies are emailed to the client.
     */
    public function reply(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string'],
            'is_internal_note' => ['nullable', 'boolean'],
        ]);

        $isInternal = $request->boolean('is_internal_note');

        $reply = $complaint->replies()->create([
            'user_id' => $request->user()->id,
            'message' => $data['message'],
            'is_internal_note' => $isInternal,
        ]);

        // Move a fresh complaint into "in progress" once staff engages.
        if ($complaint->status === 'open') {
            $complaint->update(['status' => 'in_progress']);
        }

        if (! $isInternal) {
            Mail::to($complaint->email)->send(new ComplaintReplyMail($reply));
        }

        return redirect()
            ->route('complaints.show', $complaint)
            ->with('success', $isInternal ? 'Internal note added.' : 'Reply posted and client notified.');
    }

    public function downloadAttachment(Complaint $complaint): StreamedResponse
    {
        abort_unless($complaint->hasAttachment(), 404);

        return Storage::disk('public')->download($complaint->attachment_path, $complaint->attachment_name);
    }

    public function destroy(Complaint $complaint): RedirectResponse
    {
        $complaint->delete();

        return redirect()
            ->route('complaints.index')
            ->with('success', 'Complaint deleted successfully.');
    }
}
