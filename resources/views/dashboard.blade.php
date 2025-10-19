@extends('layouts.app')

@section('title', 'Tableau de Bord Complet')

@section('content')
<div class="container-fluid">
    <!-- En-tête principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800">Tableau de Bord Complet</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->translatedFormat('l d F Y') }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('credits.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Nouveau Crédit
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-box me-1"></i> Nouveau Produit
            </a>
        </div>
    </div>

    <!-- Section Gestion de Stock -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-warehouse me-2"></i>
                Gestion de Stock - Statistiques
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total des produits</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Stock satisfaisant</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $goodStockProducts }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Stock faible</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockProducts }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Stock épuisé</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $outOfStockProducts }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Produits en alerte</h6>
                        </div>
                        <div class="card-body">
                            @if($alertProducts->isEmpty())
                                <p class="text-success">Aucun produit en alerte actuellement.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Prix</th>
                                                <th>Marque</th>
                                                <th>Taille</th>
                                                <th>Quantité</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($alertProducts as $product)
                                            <tr class="stock-{{ $product->stock_status }}">
                                                <td>{{ number_format($product->price, 2) }} €</td>
                                                <td>{{ $product->marque }}</td>
                                                <td>{{ $product->taille }}</td>
                                                <td>
                                                    @if($product->isOutOfStock())
                                                        <span class="badge bg-danger">Épuisé</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ $product->quantite }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('alerts.index') }}" class="btn btn-warning btn-sm mt-2">
                                    Voir tous les produits en alerte
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Dernières sorties de stock</h6>
                            <a href="{{ route('sorties.index') }}" class="btn btn-primary btn-sm">Voir tout</a>
                        </div>
                        <div class="card-body">
                            @if($recentMovements->isEmpty())
                                <p>Aucune sortie de stock récente.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Produit</th>
                                                <th>Quantité</th>
                                                <th>Client</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentMovements as $movement)
                                            <tr>
                                                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $movement->product->marque }} ({{ $movement->product->taille }})</td>
                                                <td>{{ $movement->quantite }}</td>
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
        </div>
    </div>

    <!-- Section Gestion de Crédit -->
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-hand-holding-usd me-2"></i>
                Gestion de Crédit - Statistiques
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-blue-50 border-left-blue-500 shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-blue-600 text-uppercase mb-1">
                                        Total Crédits</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCredits }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-invoice-dollar fa-2x text-blue-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-green-50 border-left-green-500 shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-green-600 text-uppercase mb-1">
                                        Montant Total</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalAmount, 2) }} DH</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-bill-wave fa-2x text-green-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-orange-50 border-left-orange-500 shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-orange-600 text-uppercase mb-1">
                                        Crédits Actifs</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeCredits }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-orange-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-red-50 border-left-red-500 shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-red-600 text-uppercase mb-1">
                                        Montant Restant</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRemaining, 2) }} DH</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-circle fa-2x text-red-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Évolution mensuelle ({{ date('Y') }})</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="monthlyChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Top 5 Clients</h6>
                        </div>
                        <div class="card-body">
                            @if($topClients->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($topClients as $index => $client)
                                        <div class="list-group-item d-flex align-items-center px-0">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <span class="font-weight-bold">{{ $index + 1 }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $client->name }}</h6>
                                                <small class="text-muted">{{ $client->credits_count }} crédit(s)</small>
                                            </div>
                                            <div class="flex-shrink-0 text-end">
                                                <div class="fw-bold text-success">{{ number_format($client->credits_sum_amount, 2) }} DH</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Aucune donnée disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des crédits récents -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Crédits récents avec montants restants</h6>
                            <a href="{{ route('credits.index') }}" class="btn btn-primary btn-sm">Voir tous les crédits</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="creditsTable">
                                    <thead class="bg-light">
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
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                            <small class="fw-bold">{{ strtoupper(substr($credit->client->name, 0, 1)) }}</small>
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
                                                    <span class="badge 
                                                        {{ $credit->status === 'active' ? 'bg-warning' : 
                                                           ($credit->status === 'paid' ? 'bg-success' : 'bg-danger') }}">
                                                        {{ $credit->status === 'active' ? 'Actif' : ($credit->status === 'paid' ? 'Payé' : 'Annulé') }}
                                                    </span>
                                                </td>
                                                <td>{{ $credit->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('credits.show', $credit) }}" class="btn btn-info" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('credits.edit', $credit) }}" class="btn btn-warning" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                                    <p>Aucun crédit trouvé</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($paginatedCredits->hasPages())
                                <div class="mt-3 d-flex justify-content-center">
                                    {{ $paginatedCredits->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du graphique mensuel
    const monthlyData = @json($monthlyData);
    
    const months = [];
    const counts = [];
    const amounts = [];
    
    const allMonths = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    allMonths.forEach((month, index) => {
        months.push(month);
        const monthData = monthlyData.find(d => d.month === month);
        counts.push(monthData ? monthData.count : 0);
        amounts.push(monthData ? monthData.total : 0);
    });

    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Nombre de crédits',
                    data: counts,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Montant (DH)',
                    data: amounts,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
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
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Mois'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nombre de crédits'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Montant (DH)'
                    }
                }
            }
        }
    });
});
</script>
@endsection