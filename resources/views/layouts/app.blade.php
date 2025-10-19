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
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --sidebar-width: 280px;
            --navbar-height: 76px;
        }
        
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        /* Navbar ثابتة في الأعلى */
        .navbar {
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Sidebar ثابتة على اليسار */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            overflow-y: auto;
            z-index: 1020;
            transition: all 0.3s ease;
            padding: 20px 0;
        }
        
        /* المحتوى الرئيسي */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
            padding: 20px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        /* تصميم الـ sidebar */
        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: 8px;
            margin: 4px 15px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
            border: none;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #334155;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: #3b82f6;
            color: #fff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .module-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 15px;
            border-left: 4px solid #3b82f6;
        }
        
        .module-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .navbar-logo {
            height: 45px;
            margin-right: 12px;
        }
        
        /* تحسينات للعرض على الجوال */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .main-content.sidebar-open {
                margin-left: var(--sidebar-width);
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="navbar-logo" onerror="this.style.display='none'">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                Pneumatique Aqabli
                <small class="d-block text-xs opacity-75">Gestion Complète</small>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ auth()->user()->nom ?? auth()->user()->name }} 
                            <span class="badge bg-primary ms-2">{{ auth()->user()->role ?? 'Utilisateur' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
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
        <div class="sidebar-content">
            <!-- Module Gestion de Stock -->
            <div class="module-section">
                <div class="module-title">
                    <i class="fas fa-warehouse text-blue-400"></i>
                    Gestion de Stock
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line me-2"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="fas fa-boxes me-2"></i> Liste des stocks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sorties.create') }}" class="nav-link {{ request()->routeIs('sorties.create') ? 'active' : '' }}">
                            <i class="fas fa-minus-circle me-2"></i> Sortie de stock
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('alerts.index') }}" id="alertesLink" class="nav-link {{ request()->routeIs('alerts.index') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-triangle me-2"></i> Alertes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle me-2"></i> Ajouter un produit
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Module Gestion de Crédit -->
            <div class="module-section">
                <div class="module-title">
                    <i class="fas fa-hand-holding-usd text-green-400"></i>
                    Gestion de Crédit
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('credits.index') }}" class="nav-link {{ request()->routeIs('credits.*') && !request()->routeIs('credits.create') ? 'active' : '' }}">
                            <i class="fas fa-list me-2"></i> Liste des crédits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('credits.create') }}" class="nav-link {{ request()->routeIs('credits.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle me-2"></i> Nouveau crédit
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('credits.export') }}" class="nav-link">
                            <i class="fas fa-download me-2"></i> Exporter CSV
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Actions Rapides -->
            <div class="module-section">
                <div class="module-title">
                    <i class="fas fa-bolt text-yellow-400"></i>
                    Actions Rapides
                </div>
                <div class="d-grid gap-2">
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
    @endauth

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div class="flex-grow-1">{{ session('warning') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-times-circle me-2"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Contenu Principal -->
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // إدارة الـ sidebar على الجوال
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const navbarToggler = document.querySelector('.navbar-toggler');

            if (navbarToggler && sidebar) {
                navbarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    mainContent.classList.toggle('sidebar-open');
                });
            }

            // إغلاق الـ sidebar عند النقر على رابط في الجوال
            if (window.innerWidth < 768) {
                const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('show');
                        mainContent.classList.remove('sidebar-open');
                    });
                });
            }
        });

        // Mise à jour du compteur d'alertes
        @auth
        function updateAlertCount() {
            $.ajax({
                url: '{{ route("alerts.count") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const alertLink = $('#alertesLink');
                    const icon = '<i class="fas fa-exclamation-triangle me-2"></i>';
                    
                    if (response.count > 0) {
                        alertLink.html(icon + 'Alertes <span class="badge bg-danger ms-1">' + response.count + '</span>');
                    } else {
                        alertLink.html(icon + 'Alertes');
                    }
                },
                error: function() {
                    console.log('Erreur lors de la récupération des alertes');
                }
            });
        }

        $(document).ready(function() {
            updateAlertCount();
            setInterval(updateAlertCount, 30000);
            
            // Auto-dismiss des alertes
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        });
        @endauth
    </script>
    
    @stack('scripts')
</body>
</html>