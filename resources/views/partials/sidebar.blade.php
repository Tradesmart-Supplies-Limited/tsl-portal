<aside id="layout-menu"
       class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand">

        <a href="{{ route('dashboard') }}"
           class="app-brand-link">

            <span class="app-brand-text fw-bold">

                TSL Portal

            </span>

        </a>

    </div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">

            <a href="{{ route('dashboard') }}"
               class="menu-link">

                <i class="menu-icon bx bx-home-circle"></i>

                <div>Dashboard</div>

            </a>

        </li>

        <li class="menu-header">

            <span>CLIENTS</span>

        </li>

        <li class="menu-item">

            <a href="#" class="menu-link">

                <i class="menu-icon bx bx-buildings"></i>

                <div>Client Database</div>

            </a>

        </li>

        <li class="menu-item">

            <a href="#" class="menu-link">

                <i class="menu-icon bx bx-message-square-detail"></i>

                <div>Complaints</div>

            </a>

        </li>

        <li class="menu-header">

            <span>REPORTS</span>

        </li>

        <li class="menu-item">

            <a href="#" class="menu-link">

                <i class="menu-icon bx bx-bar-chart"></i>

                <div>Reports</div>

            </a>

        </li>

        <li class="menu-header">

            <span>SYSTEM</span>

        </li>

        <li class="menu-item">

            <a href="#" class="menu-link">

                <i class="menu-icon bx bx-user"></i>

                <div>Users</div>

            </a>

        </li>

        <li class="menu-item">

            <a href="#" class="menu-link">

                <i class="menu-icon bx bx-cog"></i>

                <div>Settings</div>

            </a>

        </li>

    </ul>

</aside>