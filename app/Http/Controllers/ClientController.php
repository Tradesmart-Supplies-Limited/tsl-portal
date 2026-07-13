<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $clients = $this->filteredQuery($request)
            ->withCount(['reports', 'complaints', 'documents', 'contacts'])
            ->with(['accountManager', 'contacts' => fn ($q) => $q->where('is_primary', true)->limit(1)])
            ->paginate($request->integer('per_page') ?: 15)
            ->withQueryString();

        $stats = [
            'total' => Client::count(),
            'active' => Client::where('status', 'active')->count(),
            'inactive' => Client::where('status', 'inactive')->count(),
            'new_this_month' => Client::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        $industries = Client::whereNotNull('industry')->where('industry', '!=', '')
            ->distinct()->orderBy('industry')->pluck('industry');

        $accountManagers = User::orderBy('name')->get(['id', 'name']);

        return view('clients.index', compact('clients', 'stats', 'industries', 'accountManagers'));
    }

    public function create(): View
    {
        $accountManagers = User::orderBy('name')->get(['id', 'name']);

        return view('clients.create', compact('accountManagers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['created_by'] = $request->user()->id;

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('client-logos', 'public');
        }

        $client = Client::create($data);

        $this->syncContacts($request, $client);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client created successfully.');
    }

    public function show(Client $client): View
    {
        $client->load(['reports.uploader', 'complaints.assignee', 'contacts', 'documents.uploader', 'accountManager']);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        $client->load('contacts');
        $accountManagers = User::orderBy('name')->get(['id', 'name']);

        return view('clients.edit', compact('client', 'accountManagers'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $data = $this->validated($request, $client);

        if ($request->hasFile('logo')) {
            if ($client->logo_path) {
                Storage::disk('public')->delete($client->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('client-logos', 'public');
        }

        $client->update($data);

        $this->syncContacts($request, $client);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    /**
     * Delete several clients at once — used by the bulk-actions toolbar.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:clients,id'],
        ]);

        $count = Client::whereIn('id', $data['ids'])->count();
        Client::whereIn('id', $data['ids'])->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', "{$count} client(s) deleted successfully.");
    }

    /**
     * Export clients to CSV — respects the same filters as the index view,
     * or a specific set of IDs when exporting a selection.
     */
    public function export(Request $request): StreamedResponse
    {
        $clients = $this->filteredQuery($request)
            ->withCount(['reports', 'complaints', 'documents'])
            ->with(['accountManager', 'contacts'])
            ->get();

        $filename = 'clients-export-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($clients) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'ID', 'Name', 'Company', 'Client Type', 'Industry', 'Website', 'Tax ID',
                'Primary Email', 'Secondary Email', 'Primary Phone', 'Secondary Phone',
                'Address', 'City', 'Postal Code', 'Country', 'Status', 'Source',
                'Account Manager', 'Primary Contact Name', 'Primary Contact Title',
                'Primary Contact Email', 'Primary Contact Phone', 'All Contact Persons',
                'Reports', 'Complaints', 'Documents', 'Client Since',
            ]);

            foreach ($clients as $client) {
                $primary = $client->primary_contact;

                $allContacts = $client->contacts->map(function ($contact) {
                    return trim("{$contact->name} ({$contact->job_title}) — {$contact->email} / {$contact->phone}");
                })->implode(' | ');

                fputcsv($handle, [
                    $client->id,
                    $client->name,
                    $client->company,
                    ucfirst($client->client_type),
                    $client->industry,
                    $client->website,
                    $client->tax_id,
                    $client->email,
                    $client->secondary_email,
                    $client->phone,
                    $client->secondary_phone,
                    $client->address,
                    $client->city,
                    $client->postal_code,
                    $client->country,
                    ucfirst($client->status),
                    $client->source,
                    $client->accountManager->name ?? '',
                    $primary->name ?? '',
                    $primary->job_title ?? '',
                    $primary->email ?? '',
                    $primary->phone ?? '',
                    $allContacts,
                    $client->reports_count,
                    $client->complaints_count,
                    $client->documents_count,
                    $client->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Shared filter/search/sort logic used by both the index listing and the export.
     */
    private function filteredQuery(Request $request): Builder
    {
        return Client::search($request->get('q'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->when($request->filled('client_type'), fn ($q) => $q->where('client_type', $request->get('client_type')))
            ->when($request->filled('industry'), fn ($q) => $q->where('industry', $request->get('industry')))
            ->when($request->filled('account_manager_id'), fn ($q) => $q->where('account_manager_id', $request->get('account_manager_id')))
            ->when($request->filled('ids'), function ($q) use ($request) {
                $ids = is_array($request->get('ids')) ? $request->get('ids') : explode(',', $request->get('ids'));
                $q->whereIn('id', array_filter($ids));
            })
            ->when($request->filled('sort'), function ($q) use ($request) {
                match ($request->get('sort')) {
                    'name_asc' => $q->orderBy('name'),
                    'name_desc' => $q->orderByDesc('name'),
                    'company_asc' => $q->orderBy('company'),
                    'oldest' => $q->oldest(),
                    default => $q->latest(),
                };
            }, fn ($q) => $q->latest());
    }

    private function validated(Request $request, ?Client $client = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_type' => ['required', 'in:individual,company'],
            'company' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:clients,email,' . optional($client)->id],
            'secondary_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'secondary_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'source' => ['nullable', 'string', 'max:255'],
            'account_manager_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    /**
     * Replace the client's contact persons with whatever was submitted.
     * Rows are posted as parallel arrays: contacts[name][], contacts[email][], etc.
     */
    private function syncContacts(Request $request, Client $client): void
    {
        if (! $request->has('contacts')) {
            return;
        }

        $names = $request->input('contacts.name', []);
        $titles = $request->input('contacts.job_title', []);
        $emails = $request->input('contacts.email', []);
        $phones = $request->input('contacts.phone', []);
        $primaryIndex = $request->input('contacts.primary_index');

        $client->contacts()->delete();

        foreach ($names as $index => $name) {
            if (blank($name)) {
                continue;
            }

            $client->contacts()->create([
                'name' => $name,
                'job_title' => $titles[$index] ?? null,
                'email' => $emails[$index] ?? null,
                'phone' => $phones[$index] ?? null,
                'is_primary' => (string) $primaryIndex === (string) $index,
            ]);
        }
    }
}
