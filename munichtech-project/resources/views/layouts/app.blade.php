<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MunichTech EXPO')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --mt-primary: #0d6efd;
            --mt-dark: #0b1220;
            --mt-accent: #00d4aa;
        }
        body { background: var(--bs-body-bg); }
        .navbar-brand { font-weight: 700; letter-spacing: -0.02em; }
        .card-header-dark { background: var(--mt-dark); color: #fff; }
        .avatar-circle {
            width: 48px; height: 48px; border-radius: 50%;
            background: linear-gradient(135deg, var(--mt-primary), var(--mt-accent));
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 1.1rem;
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-lightning-charge-fill text-warning me-1"></i>MunichTech EXPO
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('collaborations.index') }}">Collaborations</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('events.index') }}">Expo</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('search.index') }}">Search</a></li>
                    @if(auth()->user()->is_admin)
                        <li class="nav-item"><a class="nav-link text-warning" href="{{ route('admin.dashboard') }}">Admin</a></li>
                    @endif
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light ms-lg-2">Sign out</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Sign in</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary btn-sm text-white px-3 ms-lg-1" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4 py-lg-5">
    @include('partials.alerts')
    @yield('content')
</main>
<footer class="bg-dark text-white-50 py-4 mt-5">
    <div class="container text-center small">
        &copy; {{ date('Y') }} MunichTech EXPO — Innovation, hackathons, and corporate collaboration platform.
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
