<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientDocumentController extends Controller
{
    public function store(Request $request, Client $client): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:contract,agreement,invoice,other'],
            'expiry_date' => ['nullable', 'date'],
            'file' => ['required', 'file', 'max:20480'],
        ]);

        $file = $request->file('file');
        $path = $file->store('client-documents/' . $client->id, 'public');

        $client->documents()->create([
            'uploaded_by' => $request->user()->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'expiry_date' => $data['expiry_date'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Document uploaded successfully.');
    }

    public function download(ClientDocument $document): StreamedResponse
    {
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function destroy(ClientDocument $document): RedirectResponse
    {
        Storage::disk('public')->delete($document->file_path);

        $clientId = $document->client_id;
        $document->delete();

        return redirect()
            ->route('clients.show', $clientId)
            ->with('success', 'Document deleted successfully.');
    }
}
