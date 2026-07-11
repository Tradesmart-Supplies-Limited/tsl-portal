<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $clients = Client::search($request->get('q'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->withCount(['reports', 'complaints', 'contacts'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('clients.index', compact('clients'));
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
