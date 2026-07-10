<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g transform="translate(-27.000000, -15.000000)">
              <g transform="translate(27.000000, 15.000000)">
                <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 L18.6192054,7.984237 L13.7918663,0.358365126 Z" fill="#696cff"></path>
              </g>
            </g>
          </g>
        </svg>
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ config('app.name', 'Client Portal') }}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Client Management</span>
    </li>

    <li class="menu-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
      <a href="{{ route('clients.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-group"></i>
        <div>Clients</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
      <a href="{{ route('reports.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div>Reports</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Support</span>
    </li>

    <li class="menu-item {{ request()->routeIs('complaints.index') || request()->routeIs('complaints.show') ? 'active' : '' }}">
      <a href="{{ route('complaints.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-error-circle"></i>
        <div>Complaint Center</div>
        @php
          $openCount = \App\Models\Complaint::whereIn('status', ['open', 'in_progress'])->count();
        @endphp
        @if ($openCount > 0)
          <div class="badge bg-danger rounded-pill ms-auto">{{ $openCount }}</div>
        @endif
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('complaints.public.create') }}" target="_blank" class="menu-link">
        <i class="menu-icon tf-icons bx bx-link-external"></i>
        <div>Public Complaint Form</div>
      </a>
    </li>
  </ul>
</aside>
