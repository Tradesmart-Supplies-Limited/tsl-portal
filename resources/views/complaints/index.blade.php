@extends('layouts.contentNavbarLayout')

@section('title', 'Complaint Center')

@section('content')
<nav aria-label="breadcrumb" class="mb-2">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Complaint Center</li>
  </ol>
</nav>

<div class="mb-4">
  <h4 class="mb-0">Complaint Center</h4>
  <small class="text-muted">Complaints submitted through the public portal</small>
</div>

@if (session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- Status summary -->
<div class="row mb-4">
  @php
    $summary = [
      'open' => ['label' => 'Open', 'icon' => 'bx-error-circle', 'color' => 'danger'],
      'in_progress' => ['label' => 'In progress', 'icon' => 'bx-loader-circle', 'color' => 'warning'],
      'resolved' => ['label' => 'Resolved', 'icon' => 'bx-check-circle', 'color' => 'success'],
      'closed' => ['label' => 'Closed', 'icon' => 'bx-lock-alt', 'color' => 'secondary'],
    ];
  @endphp
  @foreach ($summary as $key => $meta)
    <div class="col-md-3 col-6 mb-3 mb-md-0">
      <a href="{{ route('complaints.index', ['status' => $key]) }}" class="text-decoration-none">
        <div class="card">
          <div class="card-body d-flex align-items-center">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-{{ $meta['color'] }}">
                <i class="bx {{ $meta['icon'] }}"></i>
              </span>
            </div>
            <div>
              <small class="text-muted d-block">{{ $meta['label'] }}</small>
              <h5 class="mb-0">{{ \App\Models\Complaint::where('status', $key)->count() }}</h5>
            </div>
          </div>
        </div>
      </a>
    </div>
  @endforeach
</div>

<div class="card">
  <div class="card-header">
    <form method="GET" action="{{ route('complaints.index') }}" class="row g-3">
      <div class="col-md-5">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
               placeholder="Search ticket #, subject or email...">
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select">
          <option value="">All statuses</option>
          <option value="open" @selected(request('status') === 'open')>Open</option>
          <option value="in_progress" @selected(request('status') === 'in_progress')>In progress</option>
          <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
          <option value="closed" @selected(request('status') === 'closed')>Closed</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="priority" class="form-select">
          <option value="">All priorities</option>
          <option value="low" @selected(request('priority') === 'low')>Low</option>
          <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
          <option value="high" @selected(request('priority') === 'high')>High</option>
          <option value="urgent" @selected(request('priority') === 'urgent')>Urgent</option>
        </select>
      </div>
      <div class="col-md-1">
        <button type="submit" class="btn btn-outline-primary w-100"><i class="bx bx-search"></i></button>
      </div>
    </form>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Ticket</th>
          <th>Submitted by</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Assigned to</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @php
          $statusColors = ['open' => 'danger', 'in_progress' => 'warning', 'resolved' => 'success', 'closed' => 'secondary'];
          $priorityColors = ['low' => 'secondary', 'medium' => 'info', 'high' => 'warning', 'urgent' => 'danger'];
        @endphp
        @forelse ($complaints as $complaint)
          <tr>
            <td>
              <a href="{{ route('complaints.show', $complaint) }}" class="fw-semibold text-body d-block">
                #{{ $complaint->ticket_number }}
              </a>
              <small class="text-muted">{{ Str::limit($complaint->subject, 40) }}</small>
            </td>
            <td>
              <div>{{ $complaint->name }}</div>
              <small class="text-muted">{{ $complaint->email }}</small>
            </td>
            <td>
              <span class="badge bg-label-{{ $priorityColors[$complaint->priority] }}">
                {{ ucfirst($complaint->priority) }}
              </span>
            </td>
            <td>
              <span class="badge bg-label-{{ $statusColors[$complaint->status] }}">
                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
              </span>
            </td>
            <td>{{ $complaint->assignee->name ?? 'Unassigned' }}</td>
            <td>{{ $complaint->created_at->format('M d, Y') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">No complaints match this filter.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($complaints->hasPages())
    <div class="card-body">
      {{ $complaints->links() }}
    </div>
  @endif
</div>
@endsection
