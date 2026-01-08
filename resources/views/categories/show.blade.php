@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('categories.index') }}" class="text-muted text-decoration-none small mb-2 d-inline-block">
                <i class="fas fa-arrow-left me-1"></i>Retour aux catégories
            </a>
            <h2 class="mb-1">
                <i class="{{ $category->icone }}" style="color: {{ $category->couleur }}"></i>
                {{ $category->nom }}
            </h2>
            @if($category->description)
                <p class="text-muted mb-0">{{ $category->description }}</p>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Produits</p>
                            <h4 class="mb-0">{{ $stats['total_products'] }}</h4>
                        </div>
                        <i class="fas fa-boxes fa-3x text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Valeur du Stock</p>
                            <h4 class="mb-0 text-success">{{ number_format($stats['total_stock_value']) }} DH</h4>
                        </div>
                        <i class="fas fa-coins fa-3x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Stock Faible</p>
                            <h4 class="mb-0 text-warning">{{ $stats['low_stock_count'] }}</h4>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Rupture de Stock</p>
                            <h4 class="mb-0 text-danger">{{ $stats['out_of_stock'] }}</h4>
                        </div>
                        <i class="fas fa-ban fa-3x text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Produits --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Produits de la catégorie</h5>
        </div>
        <div class="card-body p-0">
            @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Quantité</th>
                            <th>Prix Achat</th>
                            <th>Prix Vente</th>
                            <th>Stock Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><strong>{{ $product->nom }}</strong></td>
                            <td>{{ $product->quantite }}</td>
                            <td>{{ number_format($product->prix_achat) }} DH</td>
                            <td>{{ number_format($product->prix_vente) }} DH</td>
                            <td>
                                <strong>{{ number_format($product->quantite * $product->prix_achat) }} DH</strong>
                            </td>
                            <td>
                                @if($product->quantite == 0)
                                    <span class="badge bg-danger">Rupture</span>
                                @elseif($product->quantite < 10)
                                    <span class="badge bg-warning text-dark">Faible</span>
                                @else
                                    <span class="badge bg-success">En stock</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4 pb-3">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <p class="text-muted">Aucun produit dans cette catégorie</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection