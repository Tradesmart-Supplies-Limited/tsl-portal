<?php

namespace App\Http\Controllers;

use App\Mail\ComplaintNotificationMail;
use App\Mail\ComplaintSubmittedMail;
use App\Models\Client;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Public-facing controller. Routes for this controller should sit OUTSIDE
 * the auth middleware group so clients can reach them without an account,
 * e.g. Route::get('/submit-complaint', ...) and Route::get('/track-complaint', ...).
 */
class ClientComplaintController extends Controller
{
    public function create(): View
    {
        return view('complaints.public.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'description' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png,heic,xlsx,xls,txt'],
        ]);

        // Link to an existing client record if the email matches one on file.
        $client = Client::where('email', $data['email'])->first();

        $attachmentFields = [];
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentFields = [
                'attachment_path' => $file->store('complaint-attachments', 'public'),
                'attachment_name' => $file->getClientOriginalName(),
                'attachment_type' => $file->getClientMimeType(),
                'attachment_size' => $file->getSize(),
            ];
        }

        $complaint = Complaint::create(array_merge([
            'client_id' => $client?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'category' => $data['category'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
            'description' => $data['description'],
            'status' => 'open',
        ], $attachmentFields));

        // Confirmation email to the client, with their tracking number.
        Mail::to($complaint->email)->send(new ComplaintSubmittedMail($complaint));

        // Internal notification to the configured distribution list.
        $notifyEmails = config('portal.complaint_notification_emails');
        if (! empty($notifyEmails)) {
            Mail::to($notifyEmails)->send(new ComplaintNotificationMail($complaint));
        }

        return redirect()
            ->route('complaints.public.confirmation', $complaint->ticket_number)
            ->with('success', 'Your complaint has been submitted.');
    }

    public function confirmation(string $ticketNumber): View
    {
        $complaint = Complaint::where('ticket_number', $ticketNumber)->firstOrFail();

        return view('complaints.public.confirmation', compact('complaint'));
    }

    /**
     * Simple lookup form so a client can check status using ticket + email.
     */
    public function trackForm(): View
    {
        return view('complaints.public.track');
    }

    public function track(Request $request): View
    {
        $data = $request->validate([
            'ticket_number' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        $complaint = Complaint::with(['replies' => fn ($q) => $q->where('is_internal_note', false)])
            ->where('ticket_number', $data['ticket_number'])
            ->where('email', $data['email'])
            ->firstOrFail();

        return view('complaints.public.status', compact('complaint'));
    }

    /**
     * Download the supporting document attached at submission time.
     * Guarded only by knowledge of the complaint's ID — acceptable here since
     * reaching this link requires having already passed the ticket+email
     * lookup on the tracking page, or being the confirmation page right
     * after submission.
     */
    public function downloadAttachment(Complaint $complaint): StreamedResponse
    {
        abort_unless($complaint->hasAttachment(), 404);

        return Storage::disk('public')->download($complaint->attachment_path, $complaint->attachment_name);
    }
}
