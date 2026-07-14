<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientImportController extends Controller
{
    /**
     * Columns expected in the uploaded CSV, in order. Header row is matched
     * case-insensitively and out-of-order — this array is only the source
     * of truth for the sample file and for validating "required" columns.
     */
    private const REQUIRED_COLUMNS = ['name', 'email'];

    private const ALL_COLUMNS = [
        'name', 'client_type', 'company', 'industry', 'website', 'tax_id',
        'email', 'secondary_email', 'phone', 'secondary_phone',
        'address', 'city', 'postal_code', 'country', 'source', 'status',
        'account_manager_email', 'notes',
        'contact_name', 'contact_job_title', 'contact_email', 'contact_phone',
    ];

    public function form(): View
    {
        return view('clients.import');
    }

    public function sample(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, self::ALL_COLUMNS);
            fputcsv($handle, [
                'Acme Logistics', 'company', 'Acme Logistics Ltd', 'Transportation', 'https://acmelogistics.example', 'TAX-00123',
                'contact@acmelogistics.example', 'billing@acmelogistics.example', '+1 555 010 2000', '',
                '123 Harbor Way', 'Portsmouth', '03801', 'USA', 'Referral', 'active',
                'manager@yourcompany.example', 'Long-standing shipping client.',
                'Jane Doe', 'Operations Manager', 'jane.doe@acmelogistics.example', '+1 555 010 2001',
            ]);
            fclose($handle);
        }, 'client-import-sample.csv', ['Content-Type' => 'text/csv']);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if (! $header) {
            fclose($handle);

            return redirect()->route('clients.import.form')->with('import_errors', ['The file appears to be empty.']);
        }

        // Normalize header: lowercase, trimmed, so "Email" / " email " / "EMAIL" all match.
        $header = array_map(fn ($col) => strtolower(trim($col)), $header);

        foreach (self::REQUIRED_COLUMNS as $required) {
            if (! in_array($required, $header, true)) {
                fclose($handle);

                return redirect()->route('clients.import.form')
                    ->with('import_errors', ["Missing required column: \"{$required}\". Download the sample CSV for the expected format."]);
            }
        }

        $created = 0;
        $skipped = 0;
        $errors = [];
        $rowNumber = 1; // header was row 1

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue; // blank line
            }

            $data = array_combine($header, array_pad($row, count($header), null));

            $name = trim((string) ($data['name'] ?? ''));
            $email = trim((string) ($data['email'] ?? ''));

            if (blank($name) || blank($email)) {
                $errors[] = "Row {$rowNumber}: name and email are both required — skipped.";
                $skipped++;

                continue;
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$rowNumber}: \"{$email}\" is not a valid email — skipped.";
                $skipped++;

                continue;
            }

            if (Client::where('email', $email)->exists()) {
                $errors[] = "Row {$rowNumber}: a client with email \"{$email}\" already exists — skipped.";
                $skipped++;

                continue;
            }

            try {
                DB::transaction(function () use ($data, $name, $email, $request) {
                    $accountManagerId = null;
                    if (! empty($data['account_manager_email'])) {
                        $accountManagerId = User::where('email', trim($data['account_manager_email']))->value('id');
                    }

                    $client = Client::create([
                        'name' => $name,
                        'client_type' => in_array($data['client_type'] ?? '', ['individual', 'company']) ? $data['client_type'] : 'company',
                        'company' => $data['company'] ?: null,
                        'industry' => $data['industry'] ?: null,
                        'website' => $data['website'] ?: null,
                        'tax_id' => $data['tax_id'] ?: null,
                        'email' => $email,
                        'secondary_email' => $data['secondary_email'] ?: null,
                        'phone' => $data['phone'] ?: null,
                        'secondary_phone' => $data['secondary_phone'] ?: null,
                        'address' => $data['address'] ?: null,
                        'city' => $data['city'] ?: null,
                        'postal_code' => $data['postal_code'] ?: null,
                        'country' => $data['country'] ?: null,
                        'source' => $data['source'] ?: 'CSV Import',
                        'status' => in_array($data['status'] ?? '', ['active', 'inactive']) ? $data['status'] : 'active',
                        'account_manager_id' => $accountManagerId,
                        'notes' => $data['notes'] ?: null,
                        'created_by' => $request->user()->id,
                    ]);

                    if (! empty($data['contact_name'])) {
                        $client->contacts()->create([
                            'name' => $data['contact_name'],
                            'job_title' => $data['contact_job_title'] ?: null,
                            'email' => $data['contact_email'] ?: null,
                            'phone' => $data['contact_phone'] ?: null,
                            'is_primary' => true,
                        ]);
                    }
                });

                $created++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$rowNumber}: could not be imported ({$e->getMessage()}).";
                $skipped++;
            }
        }

        fclose($handle);

        return redirect()->route('clients.index')
            ->with('success', "Import complete: {$created} client(s) created, {$skipped} skipped.")
            ->with('import_errors', $errors);
    }
}
