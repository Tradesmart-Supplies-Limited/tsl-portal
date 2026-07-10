<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * List all reports, optionally scoped to a single client.
     */
    public function index(Request $request): View
    {
        $reports = Report::with(['client', 'uploader'])
            ->when($request->filled('client_id'), fn ($q) => $q->where('client_id', $request->get('client_id')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $clients = Client::orderBy('name')->get(['id', 'name']);

        return view('reports.index', compact('reports', 'clients'));
    }

    public function create(Request $request): View
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $selectedClientId = $request->get('client_id');

        return view('reports.create', compact('clients', 'selectedClientId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'max:20480'], // 20MB
        ]);

        $file = $request->file('file');
        $path = $file->store('reports/' . $data['client_id'], 'public');

        $report = Report::create([
            'client_id' => $data['client_id'],
            'uploaded_by' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()
            ->route('clients.show', $report->client_id)
            ->with('success', 'Report uploaded successfully.');
    }

    public function show(Report $report): View
    {
        $report->load(['client', 'uploader']);

        return view('reports.show', compact('report'));
    }

    public function download(Report $report): StreamedResponse
    {
        return Storage::disk('public')->download($report->file_path, $report->file_name);
    }

    public function destroy(Report $report): RedirectResponse
    {
        Storage::disk('public')->delete($report->file_path);

        $clientId = $report->client_id;
        $report->delete();

        return redirect()
            ->route('clients.show', $clientId)
            ->with('success', 'Report deleted successfully.');
    }
}
