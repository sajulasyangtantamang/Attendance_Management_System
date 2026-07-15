<nav class="navbar navbar-light bg-white border-bottom px-4 py-2">
    <span class="fw-semibold text-secondary">@yield('title', 'Dashboard')</span>

    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
            <img src="{{ auth()->user()->photoUrl() }}" class="rounded-circle me-2" width="36" height="36" style="object-fit:cover;">
            {{ auth()->user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
            </li>
        </ul>
    </div>
</nav>
