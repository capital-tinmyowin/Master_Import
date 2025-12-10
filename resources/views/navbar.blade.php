<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            Import System
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('mitems.index') }}">
                        <i class="fas fa-box me-1"></i> Item Master
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-tags me-1"></i> SKU Master
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <!-- @auth -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                            id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="me-2">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                <small class="text-light opacity-75">{{ Auth::user()->email }}</small>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-cog me-2"></i> Profile Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <!-- @else -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                <!-- @endauth -->
            </ul>
        </div>

    </div>
</nav>
