@extends('layouts.app')

@section('title', 'Tableau de Bord')

@push('styles')
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #1e293b;
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* DASHBOARD HEADER */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);
            pointer-events: none;
        }

        .dashboard-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .greeting-text {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        /* STAT CARDS */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            transition: width 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }

        .stat-card:hover::before {
            width: 8px;
        }

        .stat-card.primary::before {
            background: var(--primary-color);
        }

        .stat-card.success::before {
            background: var(--success-color);
        }

        .stat-card.warning::before {
            background: var(--warning-color);
        }

        .stat-card.danger::before {
            background: var(--danger-color);
        }

        .stat-card.info::before {
            background: var(--info-color);
        }

        .stat-card.purple::before {
            background: var(--secondary-color);
        }

        .stat-card.orange::before {
            background: #f97316;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon.primary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .stat-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stat-icon.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .stat-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }

        .stat-icon.purple {
            background: rgba(139, 92, 246, 0.1);
            color: var(--secondary-color);
        }

        .stat-icon.orange {
            background: rgba(249, 115, 22, 0.1);
            color: #f97316;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-progress {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 10px;
        }

        .stat-progress-bar {
            height: 100%;
            border-radius: 2px;
            transition: width 1s ease;
        }

        /* MODERN CARD */
        .modern-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .modern-card:hover {
            box-shadow: var(--card-hover-shadow);
        }

        .modern-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .modern-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* CHART GRID */
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1200px) {
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .chart-card:hover {
            box-shadow: var(--card-hover-shadow);
            transform: translateY(-2px);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-shrink: 0;
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-container {
            position: relative;
            height: 280px;
            width: 100%;
            flex: 1;
            min-height: 250px;
        }

        .chart-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        /* BUTTONS */
        .btn-modern {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9375rem;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-modern-success {
            background: var(--success-color);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }

        .btn-modern-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
            color: white;
        }

        /* TOP PRODUCTS & CLIENTS */
        .top-product-item,
        .top-client-item {
            background: white;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 2px solid #f1f5f9;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .top-product-item:hover {
            border-color: #f97316;
            transform: translateX(5px);
            box-shadow: var(--card-shadow);
        }

        .top-client-item:hover {
            border-color: var(--primary-color);
            transform: translateX(5px);
            box-shadow: var(--card-shadow);
        }

        .product-rank,
        .rank-badge {
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .product-rank {
            background: linear-gradient(135deg, #f97316, #fb923c);
        }

        .rank-badge {
            width: 35px;
            height: 35px;
            font-size: 0.875rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .product-icon,
        .client-avatar {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .product-icon {
            background: linear-gradient(135deg, #f97316, #fb923c);
            font-size: 1.3rem;
        }

        .client-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            font-size: 1rem;
        }

        /* ACTIVITY ITEMS */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 0.75rem;
            background: #f8fafc;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .activity-item:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }

        .activity-item.credit {
            border-left-color: var(--primary-color);
        }

        .activity-item.stock {
            border-left-color: var(--info-color);
        }

        .activity-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 1rem;
            color: white;
            flex-shrink: 0;
        }

        .activity-icon.credit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .activity-icon.stock {
            background: linear-gradient(135deg, var(--info-color), #60a5fa);
        }

        /* TABLES */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-modern thead th {
            background: #f8fafc;
            color: var(--dark-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border: none;
            white-space: nowrap;
        }

        .table-modern tbody tr {
            transition: all 0.2s ease;
            background: white;
        }

        .table-modern tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.005);
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* BADGES */
        .badge-modern {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .badge-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .badge-info-gradient {
            background: linear-gradient(135deg, var(--info-color), #60a5fa);
            color: white;
        }

        .badge-success-gradient {
            background: linear-gradient(135deg, var(--success-color), #34d399);
            color: white;
        }

        .badge-warning-gradient {
            background: linear-gradient(135deg, var(--warning-color), #fbbf24);
            color: #1e293b;
        }

        .badge-danger-gradient {
            background: linear-gradient(135deg, var(--danger-color), #f87171);
            color: white;
        }

        .badge-orange-gradient {
            background: linear-gradient(135deg, #f97316, #fb923c);
            color: white;
        }

        /* ANIMATIONS */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card,
        .chart-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(5) {
            animation-delay: 0.25s;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem;
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .chart-container {
                height: 250px;
            }
        }

        .text-orange {
            color: #f97316;
        }

        /* UTILITIES */
        .container-fluid.px-4 {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            padding-top: 1rem;
            padding-bottom: 2rem;
        }

        .row.g-4 {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="greeting-text">
                        <i class="fas fa-sun"></i>
                        @php
                            $hour = now()->hour;
                            $greeting = $hour < 12 ? 'Bonjour' : ($hour < 18 ? 'Bon après-midi' : 'Bonsoir');
                        @endphp
                        <strong>{{ $greeting }}, {{ Auth::user()->name }}</strong>
                    </div>
                    <h1>Tableau de Bord</h1>
                    <p class="mb-0 opacity-90">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ now()->translatedFormat('l d F Y') }}
                        <i class="fas fa-clock ms-3 me-2"></i>
                        {{ now()->format('H:i') }}
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('credits.create') }}" class="btn btn-modern-success">
                        <i class="fas fa-plus-circle"></i>
                        Nouveau Crédit
                    </a>
                    <a href="{{ route('products.create') }}" class="btn btn-modern-primary">
                        <i class="fas fa-box"></i>
                        Nouveau Produit
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques Stock (5 cartes) -->
        <div class="row g-4 mb-4 ">
            <div class="col-xl col-md-6">
                <div class="stat-card primary">
                    <div class="stat-icon primary">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-value">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Produits</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 85%; background: var(--primary-color);"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl col-md-6">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $goodStockProducts }}</div>
                    <div class="stat-label">Stock Bon</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 70%; background: var(--success-color);"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl col-md-6">
                <div class="stat-card warning">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-value">{{ $lowStockProducts }}</div>
                    <div class="stat-label">Stock Faible</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 30%; background: var(--warning-color);"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl col-md-6">
                <div class="stat-card danger">
                    <div class="stat-icon danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-value">{{ $outOfStockProducts }}</div>
                    <div class="stat-label">Épuisé</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 15%; background: var(--danger-color);"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl col-md-6">
                <div class="stat-card orange">
                    <div class="stat-icon orange">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-value">{{ $totalCategories }}</div>
                    <div class="stat-label">Catégories</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 60%; background: #f97316;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Crédit (4 cartes) -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card purple">
                    <div class="stat-icon purple">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="stat-value">{{ $totalCredits }}</div>
                    <div class="stat-label">Total Crédits</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 75%; background: var(--secondary-color);"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-value">{{ number_format($totalAmount / 1000, 1) }}K</div>
                    <div class="stat-label">Montant Total (DH)</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 65%; background: var(--success-color);"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card info">
                    <div class="stat-icon info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ $activeCredits }}</div>
                    <div class="stat-label">Crédits Actifs</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 55%; background: var(--info-color);"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card warning">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-value">{{ number_format($totalRemaining / 1000, 1) }}K</div>
                    <div class="stat-label">Restant (DH)</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 40%; background: var(--warning-color);"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="chart-grid mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        Évolution des Crédits
                    </h5>
                    <span class="chart-badge">
                        <i class="fas fa-arrow-up"></i> Annuel
                    </span>
                </div>
                <div class="chart-container">
                    <canvas id="creditsChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Distribution du Stock
                    </h5>
                    <span class="chart-badge">
                        <i class="fas fa-box"></i> {{ $totalProducts }}
                    </span>
                </div>
                <div class="chart-container">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        Performance Mensuelle
                    </h5>
                    <span class="chart-badge">
                        <i class="fas fa-trophy"></i> Meilleur
                    </span>
                </div>
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-area"></i>
                        Top Clients
                    </h5>
                    <span class="chart-badge">
                        <i class="fas fa-crown"></i> Top 5
                    </span>
                </div>
                <div class="chart-container">
                    <canvas id="clientsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products & Top Clients -->
        <div class="row g-4 mb-4">
            <!-- Top Products This Month -->
            <div class="col-xl-12">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title">
                            <i class="fas fa-fire text-orange"></i>
                            Produits les Plus Vendus ce Mois
                        </h5>
                        <span class="badge-modern badge-orange-gradient">
                            {{ $totalSalesThisMonth }} ventes
                        </span>
                    </div>
                    @if ($topProductsThisMonth->count() > 0)
                        @foreach ($topProductsThisMonth as $index => $sortie)
                            <div class="top-product-item">
                                <div class="product-rank">{{ $index + 1 }}</div>
                                <div class="product-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold">{{ $sortie->product->name ?? '—' }}</h6>
                                    <small class="text-muted">{{ $sortie->total_sold }} vendu(s)</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">
                                        {{ number_format($sortie->total_sold * ($sortie->product->price ?? 0), 2) }} DH
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <!-- Top Clients This Month -->
            <div class="col-xl-12">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title">
                            <i class="fas fa-star text-primary"></i>
                            Meilleurs Clients ce Mois
                        </h5>
                        <span class="badge-modern badge-primary-gradient">
                            {{ $totalClientsThisMonth }} clients
                        </span>
                    </div>
                    @if ($topClientsThisMonth->count() > 0)
                        @foreach ($topClientsThisMonth as $index => $client)
                            <div class="top-client-item">
                                <div class="rank-badge">{{ $index + 1 }}</div>
                                <div class="client-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold">{{ $client->name ?? '—' }}</h6>
                                    <small class="text-muted">{{ $client->credits_count ?? 0 }} crédit(s)</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">
                                        {{ number_format($client->credits_sum_amount ?? 0, 2) }} DH
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Activity & Alerts -->
        <div class="row g-4 mb-4">
            <!-- Recent Activity -->
            <div class="col-xl-6">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title">
                            <i class="fas fa-history text-primary"></i>
                            Activité Récente
                        </h5>
                        <a href="{{ route('credits.index') }}" class="btn btn-sm btn-modern-primary">
                            Voir tout
                        </a>
                    </div>
                    <div class="activity-list">
                        @foreach ($paginatedCredits->take(3) as $credit)
                            <div class="activity-item credit">
                                <div class="activity-icon credit">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Nouveau crédit - {{ $credit->client->name }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $credit->created_at->diffForHumans() }}</small>
                                        <span class="badge-modern badge-primary-gradient">
                                            {{ number_format($credit->amount, 0) }} DH
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @foreach ($recentMovements->take(2) as $movement)
                            <div class="activity-item stock">
                                <div class="activity-icon stock">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Sortie - {{ $movement->product->marque }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $movement->created_at->diffForHumans() }}</small>
                                        <span class="badge-modern badge-info-gradient">
                                            -{{ $movement->quantite }} unités
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Alert Products -->
            <div class="col-xl-6">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title">
                            <i class="fas fa-bell text-warning"></i>
                            Produits en Alerte
                        </h5>
                        <a href="{{ route('alerts.index') }}" class="btn btn-sm btn-modern-primary">
                            Voir tout
                        </a>
                    </div>
                    @if ($alertProducts->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-success fw-500">Aucun produit en alerte</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Seuil</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alertProducts->take(5) as $product)
                                        <tr>
                                            <td>
                                                <strong>{{ $product->marque }}</strong>
                                                <div class="small text-muted">{{ $product->taille }}</div>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-bold {{ $product->quantite == 0 ? 'text-danger' : ($product->quantite <= $product->seuil_alerte ? 'text-warning' : 'text-success') }}">
                                                    {{ $product->quantite }}
                                                </span>
                                            </td>
                                            <td>{{ $product->seuil_alerte }}</td>
                                            <td>
                                                @if ($product->isOutOfStock())
                                                    <span class="badge-modern badge-danger-gradient">Épuisé</span>
                                                @else
                                                    <span class="badge-modern badge-warning-gradient">Faible</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyData = @json($monthlyData);
            const allMonths = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov',
                'Déc'
            ];

            // 1. Évolution des Crédits
            const creditsCtx = document.getElementById('creditsChart').getContext('2d');
            const counts = allMonths.map(month => {
                const data = monthlyData.find(d => d.month === month);
                return data ? data.count : 0;
            });

            const gradientCredits = creditsCtx.createLinearGradient(0, 0, 0, 300);
            gradientCredits.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
            gradientCredits.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

            new Chart(creditsCtx, {
                type: 'line',
                data: {
                    labels: allMonths,
                    datasets: [{
                        label: 'Nombre de crédits',
                        data: counts,
                        borderColor: '#6366f1',
                        backgroundColor: gradientCredits,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 2. Distribution du Stock
            const stockCtx = document.getElementById('stockChart').getContext('2d');
            new Chart(stockCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Stock Bon', 'Stock Faible', 'Épuisé'],
                    datasets: [{
                        data: [{{ $goodStockProducts }}, {{ $lowStockProducts }},
                            {{ $outOfStockProducts }}
                        ],
                        backgroundColor: ['rgba(16,185,129,0.9)', 'rgba(245,158,11,0.9)',
                            'rgba(239,68,68,0.9)'
                        ],
                        borderColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 3,
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // 3. Performance Mensuelle
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            const amounts = allMonths.map(month => {
                const data = monthlyData.find(d => d.month === month);
                return data ? (data.total / 1000).toFixed(1) : 0;
            });

            new Chart(performanceCtx, {
                type: 'bar',
                data: {
                    labels: allMonths,
                    datasets: [{
                        label: 'Montant (k DH)',
                        data: amounts,
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderRadius: 10,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: value => value + 'K DH'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 4. Top Clients
            const clientsCtx = document.getElementById('clientsChart').getContext('2d');
            const clientNames = @json($topClients->pluck('name')->toArray());
            const clientAmounts = @json($topClients->pluck('credits_sum_amount')->toArray()).map(amount => amount / 1000);

            new Chart(clientsCtx, {
                type: 'radar',
                data: {
                    labels: clientNames.length > 0 ? clientNames : ['Aucun client'],
                    datasets: [{
                        label: 'Montant (k DH)',
                        data: clientAmounts.length > 0 ? clientAmounts : [0],
                        backgroundColor: 'rgba(99, 102, 241, 0.3)',
                        borderColor: '#6366f1',
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 15
                            }
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                callback: value => value + 'K'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
