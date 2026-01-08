@extends('layouts.app')

@section('title', 'Détails du produit')

@push('styles')
<style>
    .product-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        color: #333;
        font-weight: 600;
    }

    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }

    .btn-custom {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-primary-custom {
        background: #667eea;
        color: white;
    }

    .btn-primary-custom:hover {
        background: #5a67d8;
        color: white;
        transform: translateY(-2px);
    }

    .btn-secondary-custom {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary-custom:hover {
        background: #d1d5db;
        color: #1f2937;
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Header -->
    <div class="product-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">{{ $product->marque ?? 'Produit' }}</h1>
                <p class="mb-0 opacity-90">
                    <i class="fas fa-tag me-2"></i>
                    Détails du produit #{{ $product->id }}
                </p>
            </div>
            <div>
                <span class="badge-custom badge-{{ $product->stock_status }}">
                    {{ $product->stock_status_text }}
                </span>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="info-card">
        <h5 class="mb-4">
            <i class="fas fa-info-circle me-2 text-primary"></i>
            Informations du produit
        </h5>

        <div class="info-row">
            <span class="info-label">
                <i class="fas fa-money-bill me-2"></i>Prix
            </span>
            <span class="info-value text-primary">{{ number_format($product->price, 2) }} DH</span>
        </div>

        <div class="info-row">
            <span class="info-label">
                <i class="fas fa-ruler me-2"></i>Taille
            </span>
            <span class="info-value">{{ $product->taille }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">
                <i class="fas fa-copyright me-2"></i>Marque
            </span>
            <span class="info-value">{{ $product->marque ?? 'Non spécifié' }}</span>
        </div>

        @if($product->category)
        <div class="info-row">
            <span class="info-label">
                <i class="fas fa-folder me-2"></i>Catégorie
            </span>
            <span class="info-value">{{ $product->category->nom }}</span>
        </div>
        @endif

        <div class="info-row">
            <span class="info-label">
                <i class="fas fa-cubes me-2"></i>Quantité en stock
            </span>
            <span class="info-value">{{ $product->quantite }} unités</span>
        </div>

        <div class="info-row">
            <span class="info-label">
                <i class="fas fa-exclamation-triangle me-2"></i>Seuil d'alerte
            </span>
            <span class="info-value">{{ $product->seuil_alerte }} unités</span>
        </div>

        <div class="info-row">
            <span class="info-label">
                <i class="fas fa-calendar me-2"></i>Date d'ajout
            </span>
            <span class="info-value">{{ $product->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <!-- Historique des sorties -->
    @if($product->sorties->count() > 0)
    <div class="info-card">
        <h5 class="mb-4">
            <i class="fas fa-history me-2 text-primary"></i>
            Historique des sorties ({{ $product->sorties->count() }})
        </h5>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Quantité</th>
                        <th>Prix total</th>
                        <th>Paiement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->sorties->take(5) as $sortie)
                    <tr>
                        <td>{{ $sortie->created_at->format('d/m/Y') }}</td>
                        <td>{{ $sortie->nom_client }}</td>
                        <td>{{ $sortie->quantite }}</td>
                        <td>{{ number_format($sortie->total_price, 2) }} DH</td>
                        <td>
                            <span class="badge-custom badge-{{ $sortie->payment_mode === 'cash' ? 'success' : 'warning' }}">
                                {{ $sortie->payment_mode === 'cash' ? 'Comptant' : 'Crédit' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($product->sorties->count() > 5)
        <p class="text-muted small mb-0">
            <i class="fas fa-info-circle me-1"></i>
            Affichage des 5 dernières sorties sur {{ $product->sorties->count() }} au total
        </p>
        @endif
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('products.index') }}" class="btn-custom btn-secondary-custom">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
        <a href="{{ route('products.edit', $product->id) }}" class="btn-custom btn-primary-custom">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
    </div>
</div>
@endsection