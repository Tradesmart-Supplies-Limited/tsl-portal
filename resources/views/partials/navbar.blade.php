<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3">

        <a class="nav-item nav-link px-0">

            <i class="bx bx-menu bx-sm"></i>

        </a>

    </div>

    <div class="navbar-nav-right d-flex align-items-center w-100">

        <div class="navbar-nav align-items-center">

            <div class="nav-item">

                <h5 class="mb-0">

                    TSL Portal

                </h5>

            </div>

        </div>

        <ul class="navbar-nav ms-auto">

            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle hide-arrow"
                   data-bs-toggle="dropdown">

                    <div class="avatar avatar-online">

                        <img src="{{ asset('assets/img/avatars/1.png') }}"
                             class="rounded-circle">

                    </div>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>

                        <a class="dropdown-item">

                            User Name

                        </a>

                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <form action="#"
                              method="POST">

                            @csrf

                            <button class="dropdown-item">

                                Logout

                            </button>

                        </form>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>