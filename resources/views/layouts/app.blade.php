<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pneumatique Aqabli - Gestion Complète')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        :root {
            --sidebar-width: 280px;
            --navbar-height: 70px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --dark-color: #5a5c69;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* تحسين الألوان */
        .text-gray-800 {
            color: #5a5c69 !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        /* Navbar */
        .navbar {
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .navbar-brand {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff !important;
        }

        .navbar-brand small {
            font-size: 0.75rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .navbar-logo {
            height: 40px;
            margin-right: 10px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1020;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #f8f9fc;
            flex: 1;
        }

        /* Sidebar Navigation */
        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: 0.35rem;
            margin: 3px 12px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease-in-out;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            position: relative;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .sidebar .nav-link i {
            width: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Tooltip for collapsed sidebar */
        .sidebar.collapsed .nav-link::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            background: #1e293b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.35rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .sidebar.collapsed .nav-link:hover::after {
            opacity: 1;
        }

        /* Module Sections */
        .module-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1rem;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .module-section:hover {
            background: rgba(255, 255, 255, 0.08);
            border-left-color: #667eea;
        }

        .module-title {
            color: #fff;
            font-weight: 700;
            font-size: 0.75rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 0.5rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            font-weight: 700;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        /* Buttons */
        .btn {
            border-radius: 0.35rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #653a8a 100%);
        }

        /* Tables */
        .table {
            font-size: 0.875rem;
        }

        .table thead th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #5a5c69;
            border-bottom: 2px solid #e3e6f0;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
            transform: scale(1.005);
        }

        /* Badges */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 0.35rem;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1015;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Toggle Button Styling */
        .sidebar-toggle-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 1025;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .sidebar-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .sidebar-toggle-btn:active {
            transform: scale(0.95);
        }

        /* Footer Styles - UPDATED */
        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #cbd5e1;
            padding: 2rem 0;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        .footer-links a:hover {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .footer-social {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .footer-social a {
            color: #cbd5e1;
            font-size: 1.4rem;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .footer-social a:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-2px) scale(1.1);
        }

        .footer-credits {
            font-size: 0.875rem;
            color: #94a3b8;
            text-align: center;
            width: 100%;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            line-height: 1.6;
        }

        .footer-credits a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .footer-credits a:hover {
            color: #fff;
            text-decoration: underline;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1020;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .navbar-brand small {
                display: none;
            }

            .module-section {
                margin: 0.5rem;
                padding: 0.75rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .sidebar-toggle-btn {
                display: flex;
            }

            /* Hide navbar toggle on mobile when using floating button */
            .navbar-toggler {
                display: none;
            }

            /* Footer responsive - UPDATED */
            .footer {
                padding: 1.5rem 0;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .footer-links {
                justify-content: center;
                gap: 1rem;
            }

            .footer-social {
                justify-content: center;
                gap: 1rem;
            }

            .footer-credits {
                margin-top: 1rem;
                padding-top: 1rem;
                font-size: 0.8rem;
            }
        }

        /* Desktop Sidebar Toggle */
        @media (min-width: 769px) {
            .sidebar.collapsed {
                width: 80px;
            }

            .sidebar.collapsed .module-title span,
            .sidebar.collapsed .nav-link span {
                display: none;
            }

            .sidebar.collapsed .module-section {
                padding: 0.5rem;
            }

            .sidebar.collapsed .nav-link {
                justify-content: center;
            }

            .sidebar.collapsed .nav-link i {
                margin: 0;
            }

            .main-content.expanded {
                margin-left: 80px;
            }

            .sidebar-toggle-desktop {
                position: absolute;
                top: 10px;
                right: -15px;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: 3px solid #f8f9fc;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 1021;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                transition: all 0.3s ease;
            }

            .sidebar-toggle-desktop:hover {
                transform: scale(1.1);
            }

            .sidebar.collapsed .sidebar-toggle-desktop i {
                transform: rotate(180deg);
            }

            /* Footer Desktop Adjustments */
            .footer {
                margin-left: var(--sidebar-width);
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .main-content.expanded+.footer {
                margin-left: 80px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-content>* {
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }

        .dropdown-item {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fc;
            transform: translateX(3px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="navbar-logo" onerror="this.style.display='none'">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-tire me-2"></i>Pneumatique Aqabli
                <small class="d-block">Gestion Complète</small>
            </a>

            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2"></i>
                            <span class="d-none d-md-inline">{{ auth()->user()->nom ?? auth()->user()->name }}</span>
                            <span class="badge bg-light text-dark ms-2">{{ auth()->user()->role ?? 'Utilisateur' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2"></i>Mon Profil
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Connexion
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @auth
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Desktop Toggle Button -->
        <div class="sidebar-toggle-desktop d-none d-md-flex" id="sidebarToggleDesktop" title="Réduire/Étendre">
            <i class="fas fa-chevron-left"></i>
        </div>

        <div class="sidebar-content">
            <!-- Module Gestion de Stock -->
            <div class="module-section">
                <div class="module-title">
                    <i class="fas fa-warehouse"></i>
                    <span>Gestion de Stock</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-title="Tableau de bord">
                            <i class="fas fa-chart-line"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') && !request()->routeIs('products.create') ? 'active' : '' }}" data-title="Liste des stocks">
                            <i class="fas fa-boxes"></i>
                            <span>Liste des stocks</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}" data-title="Ajouter un produit">
                            <i class="fas fa-plus-circle"></i>
                            <span>Ajouter un produit</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sorties.create') }}" class="nav-link {{ request()->routeIs('sorties.create') ? 'active' : '' }}" data-title="Sortie de stock">
                            <i class="fas fa-arrow-circle-down"></i>
                            <span>Sortie de stock</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sorties.index') }}" class="nav-link {{ request()->routeIs('sorties.index') ? 'active' : '' }}" data-title="Historique sorties">
                            <i class="fas fa-history"></i>
                            <span>Historique sorties</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('alerts.index') }}" id="alertesLink" class="nav-link {{ request()->routeIs('alerts.index') ? 'active' : '' }}" data-title="Alertes">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Alertes</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Module Gestion de Crédit -->
            <div class="module-section">
                <div class="module-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Gestion de Crédit</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('credits.index') }}" class="nav-link {{ request()->routeIs('credits.index') ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Liste des crédits</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('credits.create') }}" class="nav-link {{ request()->routeIs('credits.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>
                            <span>Nouveau crédit</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('credits.export') }}" class="nav-link">
                            <i class="fas fa-file-export"></i>
                            <span>Exporter CSV</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Actions Rapides -->
            <div class="module-section">
                <div class="module-title">
                    <i class="fas fa-bolt"></i>
                    <span>Actions Rapides</span>
                </div>
                <div class="d-grid gap-2 px-2">
                    <a href="{{ route('credits.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i> Nouveau Crédit
                    </a>
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-box me-1"></i> Nouveau Produit
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Floating Toggle Button (Mobile) -->
    <button class="sidebar-toggle-btn" id="sidebarToggleMobile" title="Menu">
        <i class="fas fa-bars"></i>
    </button>
    @endauth

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Messages Flash -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Succès!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Attention!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle me-2"></i>
            <strong>Erreur!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Contenu Principal -->
        @yield('content')
    </main>

    <!-- Footer - UPDATED -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                    <a href="{{ route('products.index') }}">
                        <i class="fas fa-warehouse me-1"></i>Stock
                    </a>
                    <a href="{{ route('credits.index') }}">
                        <i class="fas fa-hand-holding-usd me-1"></i>Crédits
                    </a>
                    <a href="#">
                        <i class="fas fa-phone me-1"></i>Contact
                    </a>
                </div>
                <div class="footer-social">
                    <a href="https://www.instagram.com/anas_ajd37" target="_blank" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" title="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
                <div class="footer-credits">
                    &copy; {{ date('Y') }} Pneumatique Aqabli - Tous droits réservés |
                    Créé par <a href="https://www.instagram.com/anas_ajd37" target="_blank">Anas Ait Daoud</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Enhanced Sidebar Toggle System
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const footer = document.querySelector('.footer');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
            const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
            const navbarToggle = document.getElementById('sidebarToggle');

            // Check if sidebar state is saved
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed' && window.innerWidth >= 769) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                if (footer) {
                    footer.style.marginLeft = '80px';
                }
            }

            // Mobile Toggle
            if (sidebarToggleMobile) {
                sidebarToggleMobile.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');

                    // Animate button icon
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('show')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }

            // Navbar Toggle (Fallback)
            if (navbarToggle) {
                navbarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }

            // Desktop Toggle
            if (sidebarToggleDesktop) {
                sidebarToggleDesktop.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');

                    // Update footer margin
                    if (footer) {
                        if (sidebar.classList.contains('collapsed')) {
                            footer.style.marginLeft = '80px';
                        } else {
                            footer.style.marginLeft = '280px';
                        }
                    }

                    // Save state
                    if (sidebar.classList.contains('collapsed')) {
                        localStorage.setItem('sidebarState', 'collapsed');
                    } else {
                        localStorage.setItem('sidebarState', 'expanded');
                    }
                });
            }

            // Overlay Click - Close Sidebar
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');

                    // Reset mobile button icon
                    if (sidebarToggleMobile) {
                        const icon = sidebarToggleMobile.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }

            // Close sidebar on link click (Mobile)
            if (window.innerWidth < 769) {
                const sidebarLinks = sidebar.querySelectorAll('.nav-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');

                        // Reset mobile button icon
                        if (sidebarToggleMobile) {
                            const icon = sidebarToggleMobile.querySelector('i');
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    });
                });
            }

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth >= 769) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');

                        // Restore desktop state
                        const savedState = localStorage.getItem('sidebarState');
                        if (savedState === 'collapsed') {
                            sidebar.classList.add('collapsed');
                            mainContent.classList.add('expanded');
                            if (footer) {
                                footer.style.marginLeft = '80px';
                            }
                        } else {
                            if (footer) {
                                footer.style.marginLeft = '280px';
                            }
                        }
                    } else {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('expanded');
                        if (footer) {
                            footer.style.marginLeft = '0';
                        }
                    }
                }, 250);
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Escape key closes sidebar on mobile
                if (e.key === 'Escape' && window.innerWidth < 769) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');

                    if (sidebarToggleMobile) {
                        const icon = sidebarToggleMobile.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });

            // Auto-dismiss alerts
            setTimeout(() => {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        });

        // تحديث عداد التنبيهات
        @auth

        function updateAlertCount() {
            $.ajax({
                url: '{{ route("alerts.count") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const alertLink = $('#alertesLink span');
                    if (response.count > 0) {
                        if (alertLink.length) {
                            alertLink.text('Alertes (' + response.count + ')');
                        } else {
                            $('#alertesLink').append(' <span class="badge bg-danger ms-1">' + response.count + '</span>');
                        }
                    }
                },
                error: function() {
                    console.log('Erreur lors de la récupération des alertes');
                }
            });
        }

        $(document).ready(function() {
            updateAlertCount();
            setInterval(updateAlertCount, 30000); // كل 30 ثانية
        });
        @endauth
    </script>
    