<nav class="navbar navbar-expand-lg bg-light shadow-sm px-4 navbar-fixed">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('logo/LOGO.png') }}" alt="Logo DIFAS" style="height: 70px;">
        </a>

        <!-- Profile Section -->
        <div class="d-flex align-items-center">
            <span class="me-3 fw-semibold">{{ Auth::user()->name }}</span>
            <div class="dropdown">
                <img src="{{ asset('logo/PROFILE.png') }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; cursor: pointer;" data-bs-toggle="dropdown">
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('password.change') }}">Change Password</a></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Log out
                        </a>
                    </li>
                </ul>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

        </div>
    </div>
</nav>
