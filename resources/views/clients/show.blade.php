@extends('layouts.contentNavbarLayout')

@section('title', $client->name)

@section('content')
<nav aria-label="breadcrumb" class="mb-2">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active">{{ $client->name }}</li>
  </ol>
</nav>

@if (session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="row">
  <!-- Client summary card -->
  <div class="col-lg-4 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <img
          src="{{ $client->logo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($client->name) . '&background=696cff&color=fff' }}"
          alt="{{ $client->name }} logo"
          class="rounded mx-auto mb-3"
          style="width: 84px; height: 84px; object-fit: cover;"
        />
        <h5 class="mb-0">{{ $client->name }}</h5>
        <small class="text-muted">{{ $client->company ?: 'No company on file' }}</small>
        <div class="mt-2 d-flex justify-content-center gap-1">
          @if ($client->status === 'active')
            <span class="badge bg-label-success">Active</span>
          @else
            <span class="badge bg-label-secondary">Inactive</span>
          @endif
          <span class="badge bg-label-info">{{ ucfirst($client->client_type) }}</span>
        </div>
      </div>
      <div class="card-body border-top">
        <ul class="list-unstyled mb-0">
          <li class="d-flex justify-content-between py-2">
            <span class="text-muted"><i class="bx bx-envelope me-1"></i> Email</span>
            <span>{{ $client->email }}</span>
          </li>
          @if ($client->secondary_email)
            <li class="d-flex justify-content-between py-2">
              <span class="text-muted"><i class="bx bx-envelope me-1"></i> Alt. email</span>
              <span>{{ $client->secondary_email }}</span>
            </li>
          @endif
          <li class="d-flex justify-content-between py-2">
            <span class="text-muted"><i class="bx bx-phone me-1"></i> Phone</span>
            <span>{{ $client->phone ?: '—' }}</span>
          </li>
          @if ($client->website)
            <li class="d-flex justify-content-between py-2">
              <span class="text-muted"><i class="bx bx-link me-1"></i> Website</span>
              <a href="{{ $client->website }}" target="_blank" rel="noopener">{{ $client->website }}</a>
            </li>
          @endif
          @if ($client->industry)
            <li class="d-flex justify-content-between py-2">
              <span class="text-muted"><i class="bx bx-briefcase me-1"></i> Industry</span>
              <span>{{ $client->industry }}</span>
            </li>
          @endif
          @if ($client->tax_id)
            <li class="d-flex justify-content-between py-2">
              <span class="text-muted"><i class="bx bx-id-card me-1"></i> Tax ID</span>
              <span>{{ $client->tax_id }}</span>
            </li>
          @endif
          <li class="d-flex justify-content-between py-2">
            <span class="text-muted"><i class="bx bx-map me-1"></i> Location</span>
            <span class="text-end">{{ collect([$client->address, $client->city, $client->postal_code, $client->country])->filter()->implode(', ') ?: '—' }}</span>
          </li>
          <li class="d-flex justify-content-between py-2">
            <span class="text-muted"><i class="bx bx-user-pin me-1"></i> Account manager</span>
            <span>{{ $client->accountManager->name ?? 'Unassigned' }}</span>
          </li>
          <li class="d-flex justify-content-between py-2">
            <span class="text-muted"><i class="bx bx-calendar me-1"></i> Client since</span>
            <span>{{ $client->created_at->format('M d, Y') }}</span>
          </li>
        </ul>
        @if ($client->notes)
          <hr>
          <p class="mb-0 text-muted">{{ $client->notes }}</p>
        @endif
      </div>
      <div class="card-body border-top d-flex gap-2">
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-primary flex-grow-1">
          <i class="bx bx-edit-alt me-1"></i> Edit
        </a>
        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="flex-grow-1"
              onsubmit="return confirm('Delete this client? This cannot be undone.')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger w-100">
            <i class="bx bx-trash me-1"></i> Delete
          </button>
        </form>
      </div>
    </div>

    <!-- Contact persons -->
    <div class="card mt-4">
      <div class="card-header">
        <h6 class="mb-0">Contact Persons</h6>
      </div>
      <div class="card-body">
        @forelse ($client->contacts as $contact)
          <div class="d-flex align-items-start mb-3">
            <div class="avatar avatar-sm flex-shrink-0 me-3">
              <span class="avatar-initial rounded-circle bg-label-primary">
                {{ strtoupper(substr($contact->name, 0, 1)) }}
              </span>
            </div>
            <div>
              <div class="d-flex align-items-center gap-1">
                <span class="fw-semibold">{{ $contact->name }}</span>
                @if ($contact->is_primary)
                  <span class="badge bg-label-primary">Primary</span>
                @endif
              </div>
              <small class="text-muted d-block">{{ $contact->job_title }}</small>
              <small class="text-muted d-block">{{ $contact->email }}</small>
              <small class="text-muted d-block">{{ $contact->phone }}</small>
            </div>
          </div>
        @empty
          <p class="text-muted mb-0">No contact persons added yet.</p>
        @endforelse
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <!-- Documents -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Documents</h5>
        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#uploadDocumentForm">
          <i class="bx bx-upload me-1"></i> Upload document
        </button>
      </div>

      <div class="collapse" id="uploadDocumentForm">
        <div class="card-body border-bottom">
          <form method="POST" action="{{ route('client-documents.store', $client) }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select" required>
                  <option value="contract">Contract</option>
                  <option value="agreement">Agreement</option>
                  <option value="invoice">Invoice</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label">Expiry date</label>
                <input type="date" name="expiry_date" class="form-control">
              </div>
              <div class="col-md-3">
                <label class="form-label">File</label>
                <input type="file" name="file" class="form-control" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm mt-3">
              <i class="bx bx-upload me-1"></i> Upload
            </button>
          </form>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-borderless">
          <tbody>
            @forelse ($client->documents as $document)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-file-blank"></i></span>
                    </div>
                    <div>
                      <span class="fw-semibold d-block">{{ $document->title }}</span>
                      <small class="text-muted">
                        {{ ucfirst($document->type) }} · {{ $document->human_file_size }}
                        @if ($document->expiry_date)
                          · Expires {{ $document->expiry_date->format('M d, Y') }}
                          @if ($document->isExpired())
                            <span class="badge bg-label-danger ms-1">Expired</span>
                          @elseif ($document->isExpiringSoon())
                            <span class="badge bg-label-warning ms-1">Expiring soon</span>
                          @endif
                        @endif
                      </small>
                    </div>
                  </div>
                </td>
                <td class="text-end">
                  <a href="{{ route('client-documents.download', $document) }}" class="btn btn-icon btn-text-secondary">
                    <i class="bx bx-download"></i>
                  </a>
                  <form action="{{ route('client-documents.destroy', $document) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete this document?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-icon btn-text-danger">
                      <i class="bx bx-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td class="text-center text-muted py-4">No documents uploaded yet — add contracts or agreements above.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Reports -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Reports</h5>
        <a href="{{ route('reports.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary">
          <i class="bx bx-upload me-1"></i> Upload report
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-borderless">
          <tbody>
            @forelse ($client->reports as $report)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial rounded bg-label-info"><i class="bx bx-file"></i></span>
                    </div>
                    <div>
                      <a href="{{ route('reports.show', $report) }}" class="fw-semibold text-body d-block">
                        {{ $report->title }}
                      </a>
                      <small class="text-muted">
                        {{ $report->file_name }} · {{ $report->human_file_size }} ·
                        {{ $report->created_at->format('M d, Y') }}
                      </small>
                    </div>
                  </div>
                </td>
                <td class="text-end">
                  <a href="{{ route('reports.download', $report) }}" class="btn btn-icon btn-text-secondary">
                    <i class="bx bx-download"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td class="text-center text-muted py-4">No reports uploaded yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Complaints -->
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Complaints</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-borderless">
          <tbody>
            @forelse ($client->complaints as $complaint)
              <tr>
                <td>
                  <a href="{{ route('complaints.show', $complaint) }}" class="fw-semibold text-body d-block">
                    {{ $complaint->subject }}
                  </a>
                  <small class="text-muted">
                    #{{ $complaint->ticket_number }} · {{ $complaint->created_at->format('M d, Y') }}
                  </small>
                </td>
                <td class="text-end">
                  @php
                    $statusColors = [
                      'open' => 'danger', 'in_progress' => 'warning',
                      'resolved' => 'success', 'closed' => 'secondary',
                    ];
                  @endphp
                  <span class="badge bg-label-{{ $statusColors[$complaint->status] ?? 'secondary' }}">
                    {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td class="text-center text-muted py-4">No complaints on file for this client.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
