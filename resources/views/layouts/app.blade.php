<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body>

<div class="layout-wrapper layout-content-navbar">

    <div class="layout-container">

        @include('partials.sidebar')

        <div class="layout-page">

            @include('partials.navbar')

            <div class="content-wrapper">

                <div class="container-xxl flex-grow-1 container-p-y">

                    @yield('content')

                </div>

                @include('partials.footer')

                <div class="content-backdrop fade"></div>

            </div>

        </div>

    </div>

    <div class="layout-overlay layout-menu-toggle"></div>

</div>

@include('partials.scripts')

@stack('scripts')

</body>
</html>