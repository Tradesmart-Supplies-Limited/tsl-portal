@extends('layouts.contentNavbarLayout')

@section('title', 'Reports')

@section('content')
<nav aria-label="breadcrumb" class="mb-2">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Reports</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="mb-0">Reports</h4>
    <small class="text-muted">All files uploaded against client records</small>
  </div>
  <a href="{{ route('reports.create') }}" class="btn btn-primary">
    <i class="bx bx-upload me-1"></i> Upload report
  </a>
</div>

@if (session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card">
  <div class="card-header">
    <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
      <div class="col-md-6">
        <select name="client_id" class="form-select" onchange="this.form.submit()">
          <option value="">All clients</option>
          @foreach ($clients as $client)
            <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>
              {{ $client->name }}
            </option>
          @endforeach
        </select>
      </div>
    </form>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Report</th>
          <th>Client</th>
          <th>Uploaded by</th>
          <th>Size</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($reports as $report)
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded bg-label-info"><i class="bx bx-file"></i></span>
                </div>
                <a href="{{ route('reports.show', $report) }}" class="text-body fw-semibold">
                  {{ $report->title }}
                </a>
              </div>
            </td>
            <td>
              <a href="{{ route('clients.show', $report->client) }}">{{ $report->client->name }}</a>
            </td>
            <td>{{ $report->uploader->name ?? 'System' }}</td>
            <td>{{ $report->human_file_size }}</td>
            <td>{{ $report->created_at->format('M d, Y') }}</td>
            <td>
              <a href="{{ route('reports.download', $report) }}" class="btn btn-icon btn-text-secondary">
                <i class="bx bx-download"></i>
              </a>
              <form action="{{ route('reports.destroy', $report) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete this report?')">
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
            <td colspan="6" class="text-center py-5 text-muted">No reports uploaded yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($reports->hasPages())
    <div class="card-body">
      {{ $reports->links() }}
    </div>
  @endif
</div>
@endsection
