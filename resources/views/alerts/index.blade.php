@extends('layouts.app')

@section('title', 'Alertes de stock')

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

    .page-header {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
    }

    .page-header h1 {
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .modern-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
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

    .alert-modern {
        border-radius: 1rem;
        padding: 1.5rem;
        border: 2px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .alert-modern.success {
        background: rgba(16, 185, 129, 0.05);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .alert-modern.warning {
        background: rgba(245, 158, 11, 0.05);
        border-color: rgba(245, 158, 11, 0.2);
    }

    .alert-modern i {
        font-size: 2rem;
    }

    .alert-modern.success i { color: var(--success-color); }
    .alert-modern.warning i { color: var(--warning-color); }

    .alert-modern-content h6 {
        font-weight: 600;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .alert-modern.success h6 { color: var(--success-color); }
    .alert-modern.warning h6 { color: var(--warning-color); }

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
        white-space: nowrap;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
        background: white;
    }

    .table-modern tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .table-modern tbody tr.danger-row {
        background: rgba(239, 68, 68, 0.05);
        border-left: 4px solid var(--danger-color);
    }

    .table-modern tbody tr.warning-row {
        background: rgba(245, 158, 11, 0.05);
        border-left: 4px solid var(--warning-color);
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
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
    .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
    .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

    .btn-modern {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .btn-modern-success {
        background: var(--success-color);
        color: white;
    }

    .btn-modern-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--success-color);
        margin-bottom: 1.5rem;
    }

    .empty-state h5 {
        color: var(--success-color);
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: #64748b;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .product-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .product-details h6 {
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--dark-color);
    }

    .product-details small {
        color: #64748b;
        font-size: 0.8125rem;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .table-modern {
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête de la page -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-1">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Alertes de stock
                </h1>
                <p class="mb-0 opacity-90">Gestion des produits en alerte de stock</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-light">
                <i class="fas fa-box"></i>
                Voir tous les produits
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    @if(!$alertProducts->isEmpty())
    <div class="row g-4 mb-4">
        @php
            $totalAlerts = $alertProducts->count();
            $outOfStock = $alertProducts->filter(fn($p) => $p->isOutOfStock())->count();
            $lowStock = $totalAlerts - $outOfStock;
        @endphp

        <div class="col-xl-4 col-md-4">
            <div class="stat-card warning">
                <div class="stat-icon warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value">{{ $totalAlerts }}</div>
                <div class="stat-label">Total Alertes</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4">
            <div class="stat-card danger">
                <div class="stat-icon danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-value">{{ $outOfStock }}</div>
                <div class="stat-label">Stock Épuisé</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4">
            <div class="stat-card warning">
                <div class="stat-icon warning">
                    <i class="fas fa-battery-quarter"></i>
                </div>
                <div class="stat-value">{{ $lowStock }}</div>
                <div class="stat-label">Stock Faible</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Contenu principal -->
    <div class="modern-card">
        @if($alertProducts->isEmpty())
            <!-- État vide - Aucune alerte -->
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <h5>Tout est en ordre !</h5>
                <p class="text-muted mb-0">Aucun produit n'est actuellement en alerte de stock.</p>
            </div>
        @else
            <!-- Alerte -->
            <div class="alert-modern warning">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="alert-modern-content flex-grow-1">
                    <h6>Attention au stock</h6>
                    <p class="mb-0 text-muted">{{ $alertProducts->count() }} produit(s) nécessitent votre attention</p>
                </div>
            </div>

            <!-- Tableau des alertes -->
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th><i class="fas fa-box me-1"></i> Produit</th>
                            <th><i class="fas fa-tag me-1"></i> Prix</th>
                            <th><i class="fas fa-cubes me-1"></i> Quantité</th>
                            <th><i class="fas fa-bell me-1"></i> Seuil</th>
                            <th><i class="fas fa-flag me-1"></i> État</th>
                            <th class="text-center"><i class="fas fa-cog me-1"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alertProducts as $product)
                            @php
                                $rowClass = $product->isOutOfStock() ? 'danger-row' : 'warning-row';
                                $statusBadge = $product->isOutOfStock() ? 'danger' : 'warning';
                                $statusIcon = $product->isOutOfStock() ? 'times-circle' : 'exclamation-triangle';
                                $statusText = $product->isOutOfStock() ? 'Épuisé' : 'Stock faible';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>
                                    <div class="product-info">
                                        <div class="product-icon">
                                            {{ strtoupper(substr($product->marque ?? 'N', 0, 2)) }}
                                        </div>
                                        <div class="product-details">
                                            <h6>{{ $product->marque ?? 'N/A' }}</h6>
                                            <small>
                                                <i class="fas fa-ruler me-1"></i>{{ $product->taille }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">
                                    {{ number_format($product->price, 2) }} DH
                                </td>
                                <td>
                                    <span class="badge-modern badge-{{ $statusBadge }}" style="font-size: 0.9375rem; padding: 0.5rem 0.75rem;">
                                        <i class="fas fa-box"></i>
                                        {{ $product->quantite }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    <i class="fas fa-bell me-1"></i>
                                    {{ $product->seuil_alerte }}
                                </td>
                                <td>
                                    <span class="badge-modern badge-{{ $statusBadge }}">
                                        <i class="fas fa-{{ $statusIcon }}"></i>
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-modern-success btn-sm">
                                        <i class="fas fa-edit"></i>
                                        Réapprovisionner
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Résumé -->
            <div class="mt-4 p-3 rounded" style="background: #f8fafc; border-left: 4px solid var(--warning-color);">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-primary"></i>
                    <span class="text-muted">
                        <strong>Conseil:</strong> Réapprovisionnez rapidement les produits épuisés et surveillez ceux en stock faible.
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection