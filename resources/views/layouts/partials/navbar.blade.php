<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Quick client search -->
    <div class="navbar-nav align-items-center">
      <form action="{{ route('clients.index') }}" method="GET" class="nav-item d-flex align-items-center w-100">
        <i class="bx bx-search fs-4 lh-0"></i>
        <input
          type="text"
          name="q"
          class="form-control border-0 shadow-none"
          placeholder="Search clients..."
          aria-label="Search clients"
        />
      </form>
    </div>

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Open complaints quick link -->
      <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
        <a class="nav-link position-relative" href="{{ route('complaints.index', ['status' => 'open']) }}">
          <i class="bx bx-bell bx-sm"></i>
          @php $navOpenCount = \App\Models\Complaint::where('status', 'open')->count(); @endphp
          @if ($navOpenCount > 0)
            <span class="badge bg-danger rounded-pill badge-notifications">{{ $navOpenCount }}</span>
          @endif
        </a>
      </li>

      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <span class="avatar-initial rounded-circle bg-label-primary">
              {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </span>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="javascript:void(0);">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                      {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">{{ auth()->user()->name ?? 'Guest' }}</span>
                  <small class="text-muted">{{ ucfirst(auth()->user()->role ?? 'staff') }}</small>
                </div>
              </div>
            </a>
          </li>
          <li><div class="dropdown-divider"></div></li>
          <li>
            <form method="POST" action="#">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Log Out</span>
              </button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
