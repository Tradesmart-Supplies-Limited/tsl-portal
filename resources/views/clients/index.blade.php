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
  <div class="d-flex gap-2">
    <a href="{{ route('clients.export', request()->query()) }}" class="btn btn-outline-secondary">
      <i class="bx bx-export me-1"></i> Export CSV
    </a>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i> New Client
    </a>
  </div>
</div>

@if (session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- Stat cards -->
<div class="row mb-4">
  <div class="col-md-3 col-6 mb-3 mb-md-0">
    <a href="{{ route('clients.index') }}" class="text-decoration-none">
      <div class="card">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group"></i></span>
          </div>
          <div>
            <small class="text-muted d-block">Total Clients</small>
            <h5 class="mb-0">{{ number_format($stats['total']) }}</h5>
          </div>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-6 mb-3 mb-md-0">
    <a href="{{ route('clients.index', ['status' => 'active']) }}" class="text-decoration-none">
      <div class="card">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-circle"></i></span>
          </div>
          <div>
            <small class="text-muted d-block">Active</small>
            <h5 class="mb-0">{{ number_format($stats['active']) }}</h5>
          </div>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-6">
    <a href="{{ route('clients.index', ['status' => 'inactive']) }}" class="text-decoration-none">
      <div class="card">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-x-circle"></i></span>
          </div>
          <div>
            <small class="text-muted d-block">Inactive</small>
            <h5 class="mb-0">{{ number_format($stats['inactive']) }}</h5>
          </div>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-6">
    <div class="card">
      <div class="card-body d-flex align-items-center">
        <div class="avatar flex-shrink-0 me-3">
          <span class="avatar-initial rounded bg-label-info"><i class="bx bx-calendar-plus"></i></span>
        </div>
        <div>
          <small class="text-muted d-block">New This Month</small>
          <h5 class="mb-0">{{ number_format($stats['new_this_month']) }}</h5>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <form method="GET" action="{{ route('clients.index') }}" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label small mb-1">Search</label>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
               placeholder="Name, company or email...">
      </div>
      <div class="col-md-2">
        <label class="form-label small mb-1">Status</label>
        <select name="status" class="form-select">
          <option value="">All statuses</option>
          <option value="active" @selected(request('status') === 'active')>Active</option>
          <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label small mb-1">Type</label>
        <select name="client_type" class="form-select">
          <option value="">All types</option>
          <option value="company" @selected(request('client_type') === 'company')>Company</option>
          <option value="individual" @selected(request('client_type') === 'individual')>Individual</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label small mb-1">Industry</label>
        <select name="industry" class="form-select">
          <option value="">All industries</option>
          @foreach ($industries as $industry)
            <option value="{{ $industry }}" @selected(request('industry') === $industry)>{{ $industry }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label small mb-1">Account manager</label>
        <select name="account_manager_id" class="form-select">
          <option value="">Anyone</option>
          @foreach ($accountManagers as $manager)
            <option value="{{ $manager->id }}" @selected((string) request('account_manager_id') === (string) $manager->id)>
              {{ $manager->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1">
        <button type="submit" class="btn btn-outline-primary w-100"><i class="bx bx-search"></i></button>
      </div>

      <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
          @if (request()->anyFilled(['q', 'status', 'client_type', 'industry', 'account_manager_id']))
            <a href="{{ route('clients.index') }}" class="small">
              <i class="bx bx-x me-1"></i> Clear filters
            </a>
          @endif
        </div>
        <div class="d-flex align-items-center gap-2">
          <label class="form-label small mb-0">Sort by</label>
          <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest first</option>
            <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
            <option value="name_asc" @selected(request('sort') === 'name_asc')>Name (A–Z)</option>
            <option value="name_desc" @selected(request('sort') === 'name_desc')>Name (Z–A)</option>
            <option value="company_asc" @selected(request('sort') === 'company_asc')>Company (A–Z)</option>
          </select>
        </div>
      </div>
    </form>
  </div>

  <!-- Bulk actions bar (hidden until rows are selected) -->
  <div id="bulkActionsBar" class="card-body border-bottom bg-lighter d-none">
    <div class="d-flex justify-content-between align-items-center">
      <span><strong id="selectedCount">0</strong> client(s) selected</span>
      <div class="d-flex gap-2">
        <button type="button" id="exportSelectedBtn" class="btn btn-sm btn-outline-secondary">
          <i class="bx bx-export me-1"></i> Export selected
        </button>
        <button type="button" id="deleteSelectedBtn" class="btn btn-sm btn-outline-danger">
          <i class="bx bx-trash me-1"></i> Delete selected
        </button>
      </div>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th style="width: 40px;">
            <input type="checkbox" id="selectAll" class="form-check-input">
          </th>
          <th>Client</th>
          <th>Type / Industry</th>
          <th>Primary Contact</th>
          <th>Reach</th>
          <th>Account Manager</th>
          <th class="text-center">Activity</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($clients as $client)
          @php $primary = $client->contacts->first(); @endphp
          <tr>
            <td>
              <input type="checkbox" class="form-check-input row-checkbox" value="{{ $client->id }}">
            </td>
            <td>
              <div class="d-flex align-items-center">
                <img
                  src="{{ $client->logo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($client->name) . '&background=696cff&color=fff' }}"
                  alt="{{ $client->name }}"
                  class="rounded me-2"
                  style="width: 36px; height: 36px; object-fit: cover;"
                />
                <div>
                  <a href="{{ route('clients.show', $client) }}" class="fw-semibold text-body d-block">
                    {{ $client->name }}
                  </a>
                  <small class="text-muted">{{ $client->company ?: '—' }}</small>
                </div>
              </div>
            </td>
            <td>
              <span class="badge bg-label-info">{{ ucfirst($client->client_type) }}</span>
              <div><small class="text-muted">{{ $client->industry ?: '—' }}</small></div>
            </td>
            <td>
              @if ($primary)
                <span class="d-block">{{ $primary->name }}</span>
                <small class="text-muted">{{ $primary->job_title }}</small>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              <div>
                <a href="mailto:{{ $client->email }}" class="text-body"><i class="bx bx-envelope me-1"></i>{{ $client->email }}</a>
              </div>
              @if ($client->phone)
                <div>
                  <a href="tel:{{ $client->phone }}" class="text-muted"><i class="bx bx-phone me-1"></i>{{ $client->phone }}</a>
                </div>
              @endif
            </td>
            <td>{{ $client->accountManager->name ?? '—' }}</td>
            <td class="text-center">
              <span class="badge bg-label-info me-1" title="Reports">
                <i class="bx bx-file"></i> {{ $client->reports_count }}
              </span>
              <span class="badge bg-label-warning me-1" title="Complaints">
                <i class="bx bx-error-circle"></i> {{ $client->complaints_count }}
              </span>
              <span class="badge bg-label-secondary" title="Documents">
                <i class="bx bx-folder"></i> {{ $client->documents_count }}
              </span>
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
                  <a class="dropdown-item" href="{{ route('clients.export', ['ids' => $client->id]) }}">
                    <i class="bx bx-export me-1"></i> Export
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
            <td colspan="9" class="text-center py-5 text-muted">
              No clients found. <a href="{{ route('clients.create') }}">Add your first client</a>.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
    <small class="text-muted">
      Showing {{ $clients->firstItem() ?? 0 }}–{{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} clients
    </small>
    @if ($clients->hasPages())
      {{ $clients->links() }}
    @endif
  </div>
</div>

<!-- Hidden form used to submit bulk delete (DELETE via method spoofing) -->
<form id="bulkDeleteForm" action="{{ route('clients.bulk-destroy') }}" method="POST" class="d-none">
  @csrf
  @method('DELETE')
</form>
@endsection

@section('page-script')
<script>
  (function () {
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = () => Array.from(document.querySelectorAll('.row-checkbox'));
    const bar = document.getElementById('bulkActionsBar');
    const countEl = document.getElementById('selectedCount');

    function refreshBar() {
      const checked = rowCheckboxes().filter(cb => cb.checked);
      countEl.textContent = checked.length;
      bar.classList.toggle('d-none', checked.length === 0);
    }

    selectAll.addEventListener('change', function () {
      rowCheckboxes().forEach(cb => cb.checked = selectAll.checked);
      refreshBar();
    });

    document.addEventListener('change', function (e) {
      if (e.target.classList.contains('row-checkbox')) {
        refreshBar();
      }
    });

    document.getElementById('exportSelectedBtn').addEventListener('click', function () {
      const ids = rowCheckboxes().filter(cb => cb.checked).map(cb => cb.value);
      if (ids.length === 0) return;
      window.location.href = "{{ route('clients.export') }}?ids=" + ids.join(',');
    });

    document.getElementById('deleteSelectedBtn').addEventListener('click', function () {
      const ids = rowCheckboxes().filter(cb => cb.checked).map(cb => cb.value);
      if (ids.length === 0) return;
      if (!confirm(`Delete ${ids.length} client(s)? This cannot be undone.`)) return;

      const form = document.getElementById('bulkDeleteForm');
      ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
      });
      form.submit();
    });
  })();
</script>
@endsection
