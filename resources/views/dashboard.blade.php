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

    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
    }

    .dashboard-header h1 {
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
        transition: width 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .stat-card:hover::before {
        width: 8px;
    }

    .stat-card.primary::before { background: var(--primary-color); }
    .stat-card.success::before { background: var(--success-color); }
    .stat-card.warning::before { background: var(--warning-color); }
    .stat-card.danger::before { background: var(--danger-color); }

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

    .stat-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary-color); }
    .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
    .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .modern-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 1.5rem;
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
    }

    .btn-modern {
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-modern-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
    }

    .btn-modern-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        color: white;
    }

    .btn-modern-success {
        background: var(--success-color);
        color: white;
    }

    .btn-modern-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
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
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
    }

    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .badge-modern {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .progress-ring {
        width: 120px;
        height: 120px;
    }

    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 0.75rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .top-client-item {
        background: white;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .top-client-item:hover {
        border-color: var(--primary-color);
        transform: translateX(5px);
    }

    .rank-badge {
        width: 35px;
        height: 35px;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    @media (max-width: 768px) {
        .stat-value {
            font-size: 1.5rem;
        }
        
        .dashboard-header h1 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête du tableau de bord -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-1">Tableau de Bord</h1>
                <p class="mb-0 opacity-90">
                    <i class="fas fa-calendar me-2"></i>
                    {{ now()->translatedFormat('l d F Y') }}
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('credits.create') }}" class="btn btn-modern-success">
                    <i class="fas fa-plus-circle"></i>
                    Nouveau Crédit
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-light">
                    <i class="fas fa-box"></i>
                    Nouveau Produit
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques Stock -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary">
                <div class="stat-icon primary">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Produits</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card success">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $goodStockProducts }}</div>
                <div class="stat-label">Stock Satisfaisant</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-icon warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value">{{ $lowStockProducts }}</div>
                <div class="stat-label">Stock Faible</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card danger">
                <div class="stat-icon danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-value">{{ $outOfStockProducts }}</div>
                <div class="stat-label">Stock Épuisé</div>
            </div>
        </div>
    </div>

    <!-- Statistiques Crédit -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary">
                <div class="stat-icon primary">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-value">{{ $totalCredits }}</div>
                <div class="stat-label">Total Crédits</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card success">
                <div class="stat-icon success">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-value">{{ number_format($totalAmount, 0) }} DH</div>
                <div class="stat-label">Montant Total</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $activeCredits }}</div>
                <div class="stat-label">Crédits Actifs</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card danger">
                <div class="stat-icon danger">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-value">{{ number_format($totalRemaining, 0) }} DH</div>
                <div class="stat-label">Montant Restant</div>
            </div>
        </div>
    </div>

    <!-- Graphiques et Top Clients -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Évolution Mensuelle {{ date('Y') }}
                    </h5>
                </div>
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="fas fa-trophy me-2 text-warning"></i>
                        Top 5 Clients
                    </h5>
                </div>
                @if($topClients->count() > 0)
                    @foreach($topClients as $index => $client)
                        <div class="top-client-item">
                            <div class="d-flex align-items-center">
                                <div class="rank-badge me-3">{{ $index + 1 }}</div>
                                <div class="client-avatar me-3">
                                    {{ strtoupper(substr($client->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold">{{ $client->name }}</h6>
                                    <small class="text-muted">{{ $client->credits_count }} crédit(s)</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">{{ number_format($client->credits_sum_amount, 0) }} DH</div>
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

    <!-- Produits en Alerte et Sorties -->
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="fas fa-bell me-2 text-warning"></i>
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
                                    <th>Prix</th>
                                    <th>Marque</th>
                                    <th>Taille</th>
                                    <th>Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alertProducts->take(5) as $product)
                                <tr>
                                    <td>{{ number_format($product->price, 2) }} €</td>
                                    <td>{{ $product->marque }}</td>
                                    <td>{{ $product->taille }}</td>
                                    <td>
                                        @if($product->isOutOfStock())
                                            <span class="badge-modern bg-danger">Épuisé</span>
                                        @else
                                            <span class="badge-modern bg-warning text-dark">{{ $product->quantite }}</span>
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
                        <i class="fas fa-exchange-alt me-2 text-info"></i>
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
                                    <th>Qté</th>
                                    <th>Client</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements->take(5) as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $movement->product->marque }} ({{ $movement->product->taille }})</td>
                                    <td><span class="badge-modern bg-info">{{ $movement->quantite }}</span></td>
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

    <!-- Crédits Récents -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="fas fa-history me-2 text-primary"></i>
                Crédits Récents
            </h5>
            <a href="{{ route('credits.index') }}" class="btn btn-sm btn-modern-primary">
                Voir tous les crédits
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Téléphone</th>
                        <th>Montant Total</th>
                        <th>Payé</th>
                        <th>Restant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedCredits as $credit)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="client-avatar me-2">
                                        {{ strtoupper(substr($credit->client->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $credit->client->name }}</div>
                                        @if($credit->reason)
                                            <small class="text-muted">{{ Str::limit($credit->reason, 30) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $credit->client->phone ?: 'N/A' }}</td>
                            <td class="fw-bold text-primary">{{ number_format($credit->amount, 2) }} DH</td>
                            <td class="fw-bold text-success">{{ number_format($credit->paid_amount, 2) }} DH</td>
                            <td class="fw-bold {{ $credit->remaining_amount > 0 ? 'text-warning' : 'text-success' }}">
                                {{ number_format($credit->remaining_amount, 2) }} DH
                            </td>
                            <td>
                                <span class="badge-modern 
                                    {{ $credit->status === 'active' ? 'bg-warning text-dark' : 
                                       ($credit->status === 'paid' ? 'bg-success' : 'bg-danger') }}">
                                    {{ $credit->status === 'active' ? 'Actif' : ($credit->status === 'paid' ? 'Payé' : 'Annulé') }}
                                </span>
                            </td>
                            <td>{{ $credit->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('credits.show', $credit) }}" class="btn btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('credits.edit', $credit) }}" class="btn btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun crédit trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paginatedCredits->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $paginatedCredits->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyData = @json($monthlyData);
    
    const allMonths = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    const months = [];
    const counts = [];
    const amounts = [];
    const remaining = [];
    
    allMonths.forEach((month, index) => {
        months.push(month);
        const monthData = monthlyData.find(d => d.month === month);
        counts.push(monthData ? monthData.count : 0);
        const total = monthData ? monthData.total : 0;
        const paid = monthData ? monthData.paid : 0;
        amounts.push(total);
        remaining.push(total - paid);
    });

    const ctx = document.getElementById('monthlyChart').getContext('2d');
    
    const gradient1 = ctx.createLinearGradient(0, 0, 0, 300);
    gradient1.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    gradient1.addColorStop(1, 'rgba(99, 102, 241, 0.01)');
    
    const gradient2 = ctx.createLinearGradient(0, 0, 0, 300);
    gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    gradient2.addColorStop(1, 'rgba(16, 185, 129, 0.01)');
    
    const gradient3 = ctx.createLinearGradient(0, 0, 0, 300);
    gradient3.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
    gradient3.addColorStop(1, 'rgba(239, 68, 68, 0.01)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Nombre de crédits',
                    data: counts,
                    borderColor: '#6366f1',
                    backgroundColor: gradient1,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y',
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'Montant Total (DH)',
                    data: amounts,
                    borderColor: '#10b981',
                    backgroundColor: gradient2,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'Montant Restant (DH)',
                    data: remaining,
                    borderColor: '#ef4444',
                    backgroundColor: gradient3,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    displayColors: true,
                    usePointStyle: true
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            return value.toLocaleString() + ' DH';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection