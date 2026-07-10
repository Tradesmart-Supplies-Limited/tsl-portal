@extends('layouts.contentNavbarLayout')

@section('title', $client->name)

@section('content')
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
        <div class="avatar avatar-xl mx-auto mb-3">
          <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 1.5rem;">
            {{ strtoupper(substr($client->name, 0, 1)) }}
          </span>
        </div>
        <h5 class="mb-0">{{ $client->name }}</h5>
        <small class="text-muted">{{ $client->company ?: 'No company on file' }}</small>
        <div class="mt-2">
          @if ($client->status === 'active')
            <span class="badge bg-label-success">Active</span>
          @else
            <span class="badge bg-label-secondary">Inactive</span>
          @endif
        </div>
      </div>
      <div class="card-body border-top">
        <ul class="list-unstyled mb-0">
          <li class="d-flex justify-content-between py-2">
            <span class="text-muted"><i class="bx bx-envelope me-1"></i> Email</span>
            <span>{{ $client->email }}</span>
          </li>
          <li class="d-flex justify-content-between py-2">
            <span class="text-muted"><i class="bx bx-phone me-1"></i> Phone</span>
            <span>{{ $client->phone ?: '—' }}</span>
          </li>
          <li class="d-flex justify-content-between py-2">
            <span class="text-muted"><i class="bx bx-map me-1"></i> Location</span>
            <span>{{ collect([$client->city, $client->country])->filter()->implode(', ') ?: '—' }}</span>
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
  </div>

  <div class="col-lg-8">
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
