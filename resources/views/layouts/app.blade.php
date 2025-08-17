{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion de Crédit')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 sidebar-transition" id="sidebar">
            <div class="flex items-center justify-center h-16 bg-blue-600">
                <h1 class="text-white text-xl font-bold">Gestion Crédit</h1>
            </div>
            
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10z"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('credits.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('credits.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Crédits
                    </a>
                    
                    <a href="{{ route('credits.create') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('credits.create') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nouveau Crédit
                    </a>
                </div>
                
                <div class="px-4 mt-8">
                    <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</h3>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('credits.export') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exporter CSV
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        {{-- Main content --}}
        <div class="flex-1 lg:ml-64">
            {{-- Header --}}
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-4 py-4">
                    <div class="flex items-center">
                        <button class="p-2 rounded-md text-gray-400 lg:hidden" id="sidebar-toggle">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div class="ml-4 lg:ml-0">
                            <h2 class="text-lg font-semibold text-gray-800">@yield('title', 'Gestion de Crédit')</h2>
                            <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <span class="text-gray-600">{{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline ml-3">
                                @csrf
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-sm font-medium transition duration-200">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Messages flash --}}
            @if(session('success'))
                <div class="mx-4 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" id="success-alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                        <svg class="fill-current h-6 w-6 text-green-500" viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.93 2.93a1 1 0 11-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 111.414-1.414l2.93 2.93 2.93-2.93a1 1 0 011.414 1.414L11.414 10l2.93 2.93a1 1 0 010 1.414z"/>
                        </svg>
                    </span>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" id="error-alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                        <svg class="fill-current h-6 w-6 text-red-500" viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.93 2.93a1 1 0 11-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 111.414-1.414l2.93 2.93 2.93-2.93a1 1 0 011.414 1.414L11.414 10l2.93 2.93a1 1 0 010 1.414z"/>
                        </svg>
                    </span>
                </div>
            @endif

            {{-- Content --}}
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Overlay pour mobile --}}
    <div class="fixed inset-0 z-40 bg-gray-600 opacity-0 pointer-events-none lg:hidden transition-opacity duration-300" id="sidebar-overlay"></div>

    <script>
        // Toggle sidebar sur mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebarToggle?.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('opacity-0');
                overlay.classList.toggle('pointer-events-none');
            });
            
            overlay?.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                overlay.classList.add('pointer-events-none');
            });

            // Auto-dismiss alerts
            setTimeout(() => {
                const successAlert = document.getElementById('success-alert');
                const errorAlert = document.getElementById('error-alert');
                if (successAlert) successAlert.style.display = 'none';
                if (errorAlert) errorAlert.style.display = 'none';
            }, 5000);
        });
    </script>
</body>
</html>