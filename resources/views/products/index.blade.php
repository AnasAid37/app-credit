@extends('layouts.app')

@section('title', 'Liste des stocks')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des stocks</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Ajouter un produit
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Rechercher par prix, taille ou marque..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <label class="input-group-text" for="sortSelect">Trier par</label>
                        <select class="form-select" id="sortSelect" name="sort">
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Prix</option>
                            <option value="taille" {{ request('sort') == 'taille' ? 'selected' : '' }}>Taille</option>
                            <option value="marque" {{ request('sort') == 'marque' ? 'selected' : '' }}>Marque</option>
                            <option value="quantite" {{ request('sort') == 'quantite' ? 'selected' : '' }}>Quantité</option>
                        </select>
                        <select class="form-select" name="order">
                            <option value="ASC" {{ request('order') == 'ASC' ? 'selected' : '' }}>Croissant</option>
                            <option value="DESC" {{ request('order') == 'DESC' ? 'selected' : '' }}>Décroissant</option>
                        </select>
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-sort"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des produits -->
    <div class="card">
        <div class="card-body">
            @if($products->isEmpty())
                <div class="alert alert-info">
                    Aucun produit trouvé.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Prix</th>
                                <th>Taille</th>
                                <th>Marque</th>
                                <th>Quantité</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="stock-{{ $product->stock_status }}">
                                    <td>{{ number_format($product->price, 2) }} €</td>
                                    <td>{{ $product->taille }}</td>
                                    <td>{{ $product->marque ?? 'N/A' }}</td>
                                    <td>{{ $product->quantite }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->stock_status }}">
                                            {{ $product->stock_status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $product->id }}, '{{ number_format($product->price, 2) }}', '{{ $product->taille }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer le produit : <strong id="productToDelete"></strong> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, price, taille) {
    document.getElementById('productToDelete').textContent = price + ' € (' + taille + ')';
    document.getElementById('deleteForm').action = '/products/' + id;
    
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection