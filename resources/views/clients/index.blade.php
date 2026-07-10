@extends('layouts.contentNavbarLayout')

@section('title', 'Clients')

@section('content')
<nav aria-label="breadcrumb" class="mb-2">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Clients</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="mb-0">Client Database</h4>
    <small class="text-muted">Manage every client record on file</small>
  </div>
  <a href="{{ route('clients.create') }}" class="btn btn-primary">
    <i class="bx bx-plus me-1"></i> New Client
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
    <form method="GET" action="{{ route('clients.index') }}" class="row g-3 align-items-center">
      <div class="col-md-6">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
               placeholder="Search by name, company or email...">
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select">
          <option value="">All statuses</option>
          <option value="active" @selected(request('status') === 'active')>Active</option>
          <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-outline-primary w-100">
          <i class="bx bx-search me-1"></i> Filter
        </button>
      </div>
    </form>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Client</th>
          <th>Company</th>
          <th>Contact</th>
          <th class="text-center">Reports</th>
          <th class="text-center">Complaints</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($clients as $client)
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    {{ strtoupper(substr($client->name, 0, 1)) }}
                  </span>
                </div>
                <a href="{{ route('clients.show', $client) }}" class="fw-semibold text-body">
                  {{ $client->name }}
                </a>
              </div>
            </td>
            <td>{{ $client->company ?: '—' }}</td>
            <td>
              <div>{{ $client->email }}</div>
              <small class="text-muted">{{ $client->phone ?: '—' }}</small>
            </td>
            <td class="text-center">
              <span class="badge bg-label-info">{{ $client->reports_count }}</span>
            </td>
            <td class="text-center">
              <span class="badge bg-label-warning">{{ $client->complaints_count }}</span>
            </td>
            <td>
              @if ($client->status === 'active')
                <span class="badge bg-label-success">Active</span>
              @else
                <span class="badge bg-label-secondary">Inactive</span>
              @endif
            </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="{{ route('clients.show', $client) }}">
                    <i class="bx bx-show-alt me-1"></i> View
                  </a>
                  <a class="dropdown-item" href="{{ route('clients.edit', $client) }}">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <form action="{{ route('clients.destroy', $client) }}" method="POST"
                        onsubmit="return confirm('Delete this client? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger">
                      <i class="bx bx-trash me-1"></i> Delete
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              No clients found. <a href="{{ route('clients.create') }}">Add your first client</a>.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($clients->hasPages())
    <div class="card-body">
      {{ $clients->links() }}
    </div>
  @endif
</div>
@endsection
