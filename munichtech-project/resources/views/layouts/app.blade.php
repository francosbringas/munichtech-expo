<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MunichTech EXPO')</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-void:      #0e0e0e;
            --bg-base:      #141414;
            --bg-raised:    #1c1c1e;
            --bg-overlay:   #242426;

            --border-subtle:  rgba(255,255,255,0.07);
            --border-default: rgba(255,255,255,0.12);
            --border-strong:  rgba(255,255,255,0.20);

            --text-primary:   #f5f5f5;
            --text-secondary: #a0a0a0;
            --text-disabled:  #555555;

            --accent:         #c8614a;
            --accent-subtle:  rgba(200,97,74,0.12);
            --accent-border:  rgba(200,97,74,0.30);

            --success:  #3d9970;
            --warning:  #c49a3c;
            --danger:   #b85450;
            --info:     #4a7fa5;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;

            --success-subtle: rgba(61,153,112,0.12);
            --success-border: rgba(61,153,112,0.30);
            --warning-subtle: rgba(196,154,60,0.12);
            --warning-border: rgba(196,154,60,0.30);
            --danger-subtle:  rgba(184,84,80,0.12);
            --danger-border:  rgba(184,84,80,0.30);
            --info-subtle:    rgba(74,127,165,0.12);
            --info-border:    rgba(74,127,165,0.30);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-void);
        }

        h1, h2, h3, h4, .h1, .h2, .h3, .h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--text-primary);
        }

        .text-muted, small, .form-text, .text-white-50 {
            color: var(--text-secondary) !important;
        }

        main.container {
            padding-top: 48px;
            padding-bottom: 64px;
        }

        main > h1, main > h2, main > .row > [class*="col"] > h1,
        main > .d-flex > h1, main > .d-flex > h2 {
            margin-bottom: 24px;
        }

        main h2 + .card, main h2 + .table-responsive, main h2 + .row > .card,
        main .display-6 + p + .row, main > .row.mb-4 + .row {
            margin-top: 0;
        }

        main > h2:not(:last-child), main .mb-4 > h4 {
            margin-bottom: 24px;
        }

        .card-body > * + * {
            margin-top: 20px;
        }

        .card-body > .row + .row,
        .card-body > form + form,
        .card-body > .table-responsive + * {
            margin-top: 24px;
        }

        /* Navbar */
        .navbar {
            background: var(--bg-base);
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            color: var(--text-primary) !important;
            letter-spacing: -0.02em;
        }

        .navbar-brand .bi {
            color: var(--accent) !important;
        }

        .navbar .nav-link {
            color: var(--text-secondary);
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            font-size: 14px;
            transition: color 0.2s ease, background 0.2s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: var(--text-primary);
            background: var(--bg-raised);
        }

        .navbar .nav-link.active {
            color: var(--accent);
        }

        .navbar .nav-link.text-warning {
            color: var(--accent) !important;
        }

        .navbar .nav-link.text-warning:hover {
            color: var(--text-primary) !important;
            background: var(--bg-raised);
        }

        .navbar-toggler {
            border-color: var(--border-default);
        }

        .navbar-toggler-icon {
            filter: invert(1) opacity(0.7);
        }

        /* Cards */
        .card {
            background: var(--bg-base);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            box-shadow: 0 1px 3px rgba(0,0,0,0.4);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            animation: fadeUp 0.35s ease forwards;
            color: var(--text-primary);
        }

        .card:hover {
            border-color: var(--border-default);
            box-shadow: 0 4px 16px rgba(0,0,0,0.5);
        }

        .card-header,
        .card-header-dark {
            background: transparent;
            border-bottom: 1px solid var(--border-subtle);
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--text-secondary);
            padding: 14px 20px;
        }

        .card-header-dark {
            color: var(--text-secondary);
        }

        .card-body {
            padding: 20px;
        }

        /* Buttons */
        .btn-primary {
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.02em;
            border-radius: var(--radius-sm);
            padding: 8px 18px;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: #b5553f;
            border-color: #b5553f;
            color: #fff;
        }

        .btn-outline-primary {
            background: transparent;
            border: 1px solid var(--accent-border);
            color: var(--accent);
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
            padding: 8px 18px;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background: var(--accent-subtle);
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-success {
            background: var(--success);
            border: 1px solid var(--success);
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
            padding: 8px 18px;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-success:hover,
        .btn-success:focus {
            filter: brightness(0.9);
            color: #fff;
        }

        .btn-danger {
            background: var(--danger);
            border: 1px solid var(--danger);
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
            padding: 8px 18px;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-danger:hover,
        .btn-danger:focus {
            filter: brightness(0.9);
            color: #fff;
        }

        .btn-secondary {
            background: var(--bg-raised);
            border: 1px solid var(--border-default);
            color: var(--text-primary);
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
            padding: 8px 18px;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            background: var(--bg-overlay);
            border-color: var(--border-strong);
            color: var(--text-primary);
        }

        .btn-outline-success {
            background: transparent;
            border: 1px solid var(--success-border);
            color: var(--success);
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
        }

        .btn-outline-success:hover {
            background: var(--success-subtle);
            border-color: var(--success);
            color: var(--success);
        }

        .btn-outline-danger {
            background: transparent;
            border: 1px solid var(--danger-border);
            color: var(--danger);
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
        }

        .btn-outline-danger:hover {
            background: var(--danger-subtle);
            border-color: var(--danger);
            color: var(--danger);
        }

        .btn-outline-warning {
            background: transparent;
            border: 1px solid var(--warning-border);
            color: var(--warning);
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
        }

        .btn-outline-warning:hover {
            background: var(--warning-subtle);
            border-color: var(--warning);
            color: var(--warning);
        }

        .btn-outline-secondary,
        .btn-outline-light {
            background: transparent;
            border: 1px solid var(--border-default);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 13px;
            border-radius: var(--radius-sm);
        }

        .btn-outline-secondary:hover,
        .btn-outline-light:hover {
            background: var(--bg-raised);
            border-color: var(--border-strong);
            color: var(--text-primary);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 12px;
        }

        /* Forms */
        .form-control,
        .form-select {
            background: var(--bg-raised);
            border: 1px solid var(--border-default);
            color: var(--text-primary);
            border-radius: var(--radius-sm);
            padding: 9px 12px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            background: var(--bg-raised);
            border-color: var(--accent);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px var(--accent-subtle);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-disabled);
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .form-select option {
            background: var(--bg-overlay);
            color: var(--text-primary);
        }

        .form-select-sm {
            min-width: 140px;
            max-width: 170px;
            font-weight: 600;
            font-size: 12px;
        }

        .form-select.bg-success {
            background-color: var(--success-subtle) !important;
            border-color: var(--success-border) !important;
            color: var(--success) !important;
        }

        .form-select.bg-primary {
            background-color: var(--accent-subtle) !important;
            border-color: var(--accent-border) !important;
            color: var(--accent) !important;
        }

        .form-select.bg-warning {
            background-color: var(--warning-subtle) !important;
            border-color: var(--warning-border) !important;
            color: var(--warning) !important;
        }

        .form-select.bg-light {
            background-color: var(--bg-raised) !important;
            border-color: var(--border-default) !important;
            color: var(--text-secondary) !important;
        }

        /* Tables */
        .table {
            color: var(--text-primary);
            border-color: var(--border-subtle);
            --bs-table-bg: transparent;
            --bs-table-striped-bg: transparent;
            --bs-table-hover-bg: var(--bg-raised);
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: var(--border-subtle);
        }

        .table thead th {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--text-secondary);
            background: var(--bg-base);
            border-bottom: 1px solid var(--border-default);
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 12px 16px;
            border-color: var(--border-subtle);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--bg-raised);
        }

        .table-striped > tbody > tr:nth-of-type(odd) > * {
            --bs-table-bg-type: transparent;
        }

        /* Badges */
        .badge {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.05em;
            border-radius: var(--radius-sm);
            padding: 4px 8px;
        }

        .badge.bg-primary,
        .bg-primary.badge {
            background-color: var(--accent-subtle) !important;
            color: var(--accent) !important;
            border: 1px solid var(--accent-border);
        }

        .badge.bg-success,
        .bg-success.badge {
            background-color: var(--success-subtle) !important;
            color: var(--success) !important;
            border: 1px solid var(--success-border);
        }

        .badge.bg-warning,
        .bg-warning.badge {
            background-color: var(--warning-subtle) !important;
            color: var(--warning) !important;
            border: 1px solid var(--warning-border);
        }

        .badge.bg-danger,
        .bg-danger.badge {
            background-color: var(--danger-subtle) !important;
            color: var(--danger) !important;
            border: 1px solid var(--danger-border);
        }

        .badge.bg-info,
        .bg-info.badge {
            background-color: var(--info-subtle) !important;
            color: var(--info) !important;
            border: 1px solid var(--info-border);
        }

        .badge.bg-secondary,
        .bg-secondary.badge {
            background-color: var(--bg-raised) !important;
            color: var(--text-secondary) !important;
            border: 1px solid var(--border-default);
        }

        .badge.bg-light,
        .bg-light.badge {
            background-color: var(--bg-raised) !important;
            color: var(--text-secondary) !important;
            border: 1px solid var(--border-default);
        }

        .badge.text-dark,
        .bg-warning.text-dark {
            color: var(--warning) !important;
        }

        /* Alerts */
        .alert-success {
            background: var(--success-subtle);
            border: 1px solid var(--success-border);
            border-left: 3px solid var(--success);
            color: var(--text-primary);
        }

        .alert-danger {
            background: var(--danger-subtle);
            border: 1px solid var(--danger-border);
            border-left: 3px solid var(--danger);
            color: var(--text-primary);
        }

        .alert-warning {
            background: var(--warning-subtle);
            border: 1px solid var(--warning-border);
            border-left: 3px solid var(--warning);
            color: var(--text-primary);
        }

        .alert-info {
            background: var(--info-subtle);
            border: 1px solid var(--info-border);
            border-left: 3px solid var(--info);
            color: var(--text-primary);
        }

        /* Utility overrides */
        .bg-white {
            background-color: var(--bg-base) !important;
        }

        .bg-light {
            background-color: var(--bg-raised) !important;
            color: var(--text-secondary) !important;
        }

        .bg-body-secondary {
            background-color: var(--bg-raised) !important;
        }

        .bg-dark {
            background-color: var(--bg-base) !important;
        }

        .text-dark {
            color: var(--text-primary) !important;
        }

        .text-primary {
            color: var(--accent) !important;
        }

        .text-success {
            color: var(--success) !important;
        }

        .text-warning {
            color: var(--warning) !important;
        }

        .text-info {
            color: var(--info) !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .text-white {
            color: var(--text-primary) !important;
        }

        .bg-primary {
            background-color: var(--accent) !important;
        }

        .bg-primary.bg-opacity-10 {
            background-color: var(--accent-subtle) !important;
        }

        .bg-success {
            background-color: var(--success) !important;
        }

        .bg-info.bg-opacity-10 {
            background-color: var(--info-subtle) !important;
        }

        .bg-info {
            background-color: var(--info) !important;
        }

        .bg-warning {
            background-color: var(--warning-subtle) !important;
            color: var(--warning) !important;
        }

        .bg-secondary {
            background-color: var(--bg-raised) !important;
            color: var(--text-secondary) !important;
        }

        .border {
            border-color: var(--border-subtle) !important;
        }

        /* Avatar */
        .avatar-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--accent-subtle);
            border: 1px solid var(--accent-border);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* List group */
        .list-group-item {
            background: transparent;
            border-color: var(--border-subtle);
            color: var(--text-primary);
        }

        /* Progress */
        .progress {
            background-color: var(--bg-raised);
            border-radius: var(--radius-sm);
        }

        .progress-bar {
            background: var(--success);
            color: var(--text-primary);
            font-size: 12px;
            font-weight: 600;
        }

        .progress-bar.bg-success {
            background: var(--success);
        }

        /* Modal */
        .modal-content {
            background: var(--bg-base);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: var(--radius-lg);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-subtle);
        }

        .modal-footer {
            border-top: 1px solid var(--border-subtle);
        }

        .btn-close {
            filter: invert(1) opacity(0.6);
        }

        /* Carousel */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: var(--bg-raised);
            border: 1px solid var(--border-default);
            border-radius: var(--radius-sm);
            padding: 12px;
            background-size: 50%;
        }

        /* Dropdown / pagination */
        .dropdown-menu {
            background: var(--bg-overlay);
            border: 1px solid var(--border-default);
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background: var(--bg-raised);
            color: var(--text-primary);
        }

        .page-link {
            background: var(--bg-raised);
            border-color: var(--border-default);
            color: var(--text-secondary);
        }

        .page-link:hover {
            background: var(--bg-overlay);
            color: var(--text-primary);
        }

        hr {
            border-color: var(--border-subtle);
            opacity: 1;
        }

        a {
            color: var(--accent);
        }

        a:hover {
            color: #b5553f;
        }

        .display-6.text-warning {
            color: var(--warning) !important;
        }

        /* Corporate footer */
        .site-footer {
            background: var(--bg-base);
            border-top: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            padding-top: 64px;
            padding-bottom: 32px;
        }

        .footer-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 15px;
            letter-spacing: 0.12em;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .footer-desc {
            font-size: 13px;
            line-height: 1.7;
            color: var(--text-secondary);
            max-width: 280px;
        }

        .footer-heading {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--text-primary);
        }

        .footer-location {
            font-size: 13px;
            line-height: 1.7;
            color: var(--text-secondary);
        }

        .footer-location strong {
            display: block;
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 6px;
        }

        .footer-bottom {
            border-top: 1px solid var(--border-subtle);
            margin-top: 48px;
            padding-top: 28px;
        }

        .footer-social {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 1px solid var(--border-default);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-size: 16px;
            transition: color 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        }

        .footer-social a:hover {
            color: var(--text-primary);
            border-color: var(--border-strong);
            background: var(--bg-raised);
        }

        .footer-copyright {
            font-size: 12px;
            color: var(--text-disabled);
            margin: 0;
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg bg-transparent">
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
                    <li class="nav-item"><a class="nav-link btn btn-primary btn-sm px-3 ms-lg-1" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<main class="container flex-grow-1">
    @include('partials.alerts')
    @yield('content')
</main>
<footer class="site-footer mt-auto">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">MUNICH TECH EXPO</div>
                <p class="footer-desc mb-0">
                    Europe's premier deep-tech gathering in Munich — where enterprise R&amp;D, venture capital, and frontier innovation converge at Messe München.
                </p>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="footer-heading">Legal</div>
                <ul class="footer-links">
                    <li><a href="#">General Terms of Business</a></li>
                    <li><a href="#">Delegate Terms of Business</a></li>
                    <li><a href="#">Website Terms of Use</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Cookies Policy</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="footer-heading">Company</div>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Press Kit</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="footer-heading">Location</div>
                <p class="footer-location mb-0">
                    <strong>Sede Central</strong>
                    Munich, Germany — Messe München Exhibition Center
                </p>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="footer-social">
                <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            </div>
            <p class="footer-copyright mb-0">&copy; Copyright MunichTech Expo. All rights reserved.</p>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
