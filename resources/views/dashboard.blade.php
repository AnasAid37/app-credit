@extends('layouts.app')

@section('title', 'Tableau de Bord')

@push('styles')
<style>
    :root {
        --primary-color: #6d28d9;
        --primary-light: #8b5cf6;
        --secondary-color: #0ea5e9;
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
        background-color: #f1f5f9;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .container-fluid {
        max-width: 1400px;
        padding: 20px;
    }

    /* En-tête avec salutation */
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-hover-shadow);
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
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);
    }

    .greeting-text {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
        margin-bottom: 10px;
        opacity: 0.9;
    }

    .greeting-text i {
        font-size: 1.5rem;
    }

    .dashboard-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255,255,255,0.2);
        padding: 8px 20px;
        border-radius: 50px;
        backdrop-filter: blur(10px);
        margin-top: 10px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    /* Stat Cards - نسخة محسنة */
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
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary-color), var(--primary-light));
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .stat-card:hover::before {
        width: 8px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1rem;
        color: white;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .stat-icon.primary { 
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    }
    .stat-icon.success { 
        background: linear-gradient(135deg, var(--success-color), #34d399);
    }
    .stat-icon.warning { 
        background: linear-gradient(135deg, var(--warning-color), #fbbf24);
    }
    .stat-icon.danger { 
        background: linear-gradient(135deg, var(--danger-color), #f87171);
    }
    .stat-icon.info { 
        background: linear-gradient(135deg, var(--info-color), #60a5fa);
    }
    .stat-icon.purple { 
        background: linear-gradient(135deg, #8b5cf6, #a78bfa);
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--dark-color), #475569);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.25rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
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
        background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
        border-radius: 2px;
    }

    /* Modern Card Design */
    .modern-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 1.5rem;
        height: 100%;
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
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modern-card-title i {
        color: var(--primary-color);
    }

    /* Buttons */
    .btn-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .btn-modern-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        box-shadow: 0 4px 15px rgba(109, 40, 217, 0.2);
    }

    .btn-modern-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(109, 40, 217, 0.3);
        color: white;
    }

    .btn-modern-success {
        background: linear-gradient(135deg, var(--success-color), #34d399);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }

    .btn-modern-success:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        color: white;
    }

    /* Charts Section */
    .charts-container {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
    }

    .chart-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
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
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .chart-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
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
        height: 300px;
        width: 100%;
    }

    /* Activity Items */
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

    .activity-item.payment {
        border-left-color: var(--success-color);
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
    }

    .activity-icon.credit { background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); }
    .activity-icon.stock { background: linear-gradient(135deg, var(--info-color), #60a5fa); }
    .activity-icon.payment { background: linear-gradient(135deg, var(--success-color), #34d399); }

    /* Top Clients */
    .top-client-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
    }

    .top-client-item:hover {
        border-color: var(--primary-color);
        transform: translateX(5px);
        background: white;
    }

    .rank-badge {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        margin-right: 1rem;
        box-shadow: 0 4px 10px rgba(109, 40, 217, 0.2);
    }

    .client-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        margin-right: 1rem;
    }

    /* Badges */
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .bg-primary-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
    }

    .bg-success-gradient {
        background: linear-gradient(135deg, var(--success-color), #34d399);
        color: white;
    }

    .bg-warning-gradient {
        background: linear-gradient(135deg, var(--warning-color), #fbbf24);
        color: #1e293b;
    }

    .bg-danger-gradient {
        background: linear-gradient(135deg, var(--danger-color), #f87171);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }
        
        .dashboard-header {
            padding: 1.5rem;
        }
        
        .dashboard-header h1 {
            font-size: 1.8rem;
        }
        
        .stat-value {
            font-size: 1.8rem;
        }
        
        .chart-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .chart-card {
            padding: 1rem;
        }
        
        .chart-container {
            height: 250px;
        }
    }

    /* Animations */
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

    .stat-card {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête du tableau de bord avec salutation -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="greeting-text">
                    <i class="fas fa-sun"></i>
                    @php
                        $hour = now()->hour;
                        if ($hour < 12) {
                            $greeting = 'Bonjour';
                        } elseif ($hour < 18) {
                            $greeting = 'Bon après-midi';
                        } else {
                            $greeting = 'Bonsoir';
                        }
                    @endphp
                    <strong>{{ $greeting }},</strong>
                </div>
                <h1>
                    Tableau de Bord
                    <div class="user-badge">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <strong>{{ Auth::user()->name }}</strong>
                            <div class="small opacity-75">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </h1>
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

    <!-- Statistiques Stock -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Produits</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 85%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $goodStockProducts }}</div>
                <div class="stat-label">Stock Satisfaisant</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 70%; background: linear-gradient(90deg, #10b981, #34d399);"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value">{{ $lowStockProducts }}</div>
                <div class="stat-label">Stock Faible</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 30%; background: linear-gradient(90deg, #f59e0b, #fbbf24);"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-value">{{ $outOfStockProducts }}</div>
                <div class="stat-label">Stock Épuisé</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 15%; background: linear-gradient(90deg, #ef4444, #f87171);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Crédit -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-value">{{ $totalCredits }}</div>
                <div class="stat-label">Total Crédits</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 75%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-value">{{ number_format($totalAmount/1000, 1) }}K</div>
                <div class="stat-label">Montant Total (DH)</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 65%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $activeCredits }}</div>
                <div class="stat-label">Crédits Actifs</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 55%; background: linear-gradient(90deg, #3b82f6, #60a5fa);"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-value">{{ number_format($totalRemaining/1000, 1) }}K</div>
                <div class="stat-label">Montant Restant (DH)</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 40%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques Section -->
    <div class="chart-grid mb-4">
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    Évolution des Crédits {{ date('Y') }}
                </h5>
                <span class="badge-modern bg-primary-gradient">
                    <i class="fas fa-arrow-up"></i>
                    15% vs mois dernier
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
                <span class="badge-modern bg-info-gradient">
                    <i class="fas fa-box"></i>
                    {{ $totalProducts }} produits
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
                <span class="badge-modern bg-success-gradient">
                    <i class="fas fa-trophy"></i>
                    Meilleur: {{ now()->format('F') }}
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
                    Top Clients - Montants
                </h5>
                <span class="badge-modern bg-warning-gradient">
                    <i class="fas fa-crown"></i>
                    Top 5
                </span>
            </div>
            <div class="chart-container">
                <canvas id="clientsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Clients et Activité Récente -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="fas fa-trophy text-warning"></i>
                        Top 5 Clients
                    </h5>
                </div>
                @if($topClients->count() > 0)
                    @foreach($topClients as $index => $client)
                        <div class="top-client-item">
                            <div class="rank-badge">{{ $index + 1 }}</div>
                            <div class="client-avatar me-3">
                                {{ strtoupper(substr($client->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">{{ $client->name }}</h6>
                                <small class="text-muted">{{ $client->credits_count }} crédit(s)</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ number_format($client->credits_sum_amount/1000, 1) }}K DH</div>
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

        <div class="col-xl-8">
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
                    @foreach($paginatedCredits->take(3) as $credit)
                    <div class="activity-item credit">
                        <div class="activity-icon credit">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Nouveau crédit - {{ $credit->client->name }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $credit->created_at->diffForHumans() }}</small>
                                <span class="badge-modern bg-primary-gradient">
                                    {{ number_format($credit->amount, 0) }} DH
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @foreach($recentMovements->take(2) as $movement)
                    <div class="activity-item stock">
                        <div class="activity-icon stock">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Sortie stock - {{ $movement->product->marque }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $movement->created_at->diffForHumans() }}</small>
                                <span class="badge-modern bg-info-gradient">
                                    -{{ $movement->quantite }} unités
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Produits en Alerte et Sorties -->
    <div class="row g-4 mb-4">
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
                @if($alertProducts->isEmpty())
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
                                @foreach($alertProducts->take(5) as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->marque }}</strong>
                                        <div class="small text-muted">{{ $product->taille }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $product->quantite == 0 ? 'text-danger' : ($product->quantite <= $product->seuil_alerte ? 'text-warning' : 'text-success') }}">
                                            {{ $product->quantite }}
                                        </span>
                                    </td>
                                    <td>{{ $product->seuil_alerte }}</td>
                                    <td>
                                        @if($product->isOutOfStock())
                                            <span class="badge-modern bg-danger-gradient">Épuisé</span>
                                        @else
                                            <span class="badge-modern bg-warning-gradient">Faible</span>
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

        <div class="col-xl-6">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="fas fa-exchange-alt text-info"></i>
                        Dernières Sorties
                    </h5>
                    <a href="{{ route('sorties.index') }}" class="btn btn-sm btn-modern-primary">
                        Voir tout
                    </a>
                </div>
                @if($recentMovements->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune sortie récente</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Client</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements->take(5) as $movement)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $movement->created_at->format('d/m') }}</small>
                                        <div class="small">{{ $movement->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <strong>{{ $movement->product->marque }}</strong>
                                        <div class="small text-muted">{{ $movement->product->taille }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-modern bg-info-gradient">{{ $movement->quantite }}</span>
                                    </td>
                                    <td>{{ $movement->nom_client }}</td>
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
    // Données pour les graphiques
    const monthlyData = @json($monthlyData);
    const allMonths = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    // 1. Graphique Évolution des Crédits
    const creditsCtx = document.getElementById('creditsChart').getContext('2d');
    const counts = allMonths.map(month => {
        const data = monthlyData.find(d => d.month === month);
        return data ? data.count : 0;
    });

    new Chart(creditsCtx, {
        type: 'line',
        data: {
            labels: allMonths,
            datasets: [{
                label: 'Nombre de crédits',
                data: counts,
                borderColor: '#6d28d9',
                backgroundColor: 'rgba(109, 40, 217, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6d28d9',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
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

    // 2. Graphique Distribution du Stock
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'doughnut',
        data: {
            labels: ['Stock Bon', 'Stock Faible', 'Stock Épuisé'],
            datasets: [{
                data: [{{ $goodStockProducts }}, {{ $lowStockProducts }}, {{ $outOfStockProducts }}],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '65%'
        }
    });

    // 3. Graphique Performance Mensuelle (Montants)
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
                backgroundColor: [
                    'rgba(109, 40, 217, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(167, 139, 250, 0.8)',
                    'rgba(196, 181, 253, 0.8)'
                ],
                borderRadius: 8,
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
                    ticks: {
                        callback: function(value) {
                            return value + 'K';
                        }
                    }
                }
            }
        }
    });

    // 4. Graphique Top Clients
    const clientsCtx = document.getElementById('clientsChart').getContext('2d');
    const clientNames = @json($topClients->pluck('name')->toArray());
    const clientAmounts = @json($topClients->pluck('credits_sum_amount')->toArray()).map(amount => amount / 1000);

    new Chart(clientsCtx, {
        type: 'polarArea',
        data: {
            labels: clientNames,
            datasets: [{
                data: clientAmounts,
                backgroundColor: [
                    'rgba(109, 40, 217, 0.7)',
                    'rgba(139, 92, 246, 0.7)',
                    'rgba(167, 139, 250, 0.7)',
                    'rgba(196, 181, 253, 0.7)',
                    'rgba(224, 231, 255, 0.7)'
                ],
                borderColor: [
                    '#6d28d9',
                    '#8b5cf6',
                    '#a78bfa',
                    '#c4b5fd',
                    '#e0e7ff'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
});
</script>
@endsection