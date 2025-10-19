@extends('layouts.app')

@section('title', 'Sortie de stock')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Sortie de stock</h1>

    <div class="card">
        <div class="card-body">
            @if(empty($products))
            <div class="alert alert-warning">
                Aucun produit disponible en stock. <a href="{{ route('products.create') }}" class="alert-link">Ajouter des produits</a>
            </div>
            @else
            <form method="POST" action="{{ route('sorties.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="produit_id" class="form-label">Produit *</label>
                    <select class="form-select @error('produit_id') is-invalid @enderror" id="produit_id" name="produit_id" required>
                        <option value="">-- Sélectionner un produit --</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-quantite="{{ $product->quantite }}" {{ old('produit_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->marque ?? 'N/A' }} ({{ $product->taille }}) - Prix: {{ number_format($product->price, 2) }} € - Stock: {{ $product->quantite }}
                        </option>
                        @endforeach
                    </select>
                    @error('produit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="quantite" class="form-label">Quantité *</label>
                    <input type="number" class="form-control @error('quantite') is-invalid @enderror" 
                           id="quantite" name="quantite" min="1" value="{{ old('quantite', 1) }}" required>
                    <div class="form-text" id="stock-available"></div>
                    @error('quantite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nom_client" class="form-label">Nom du client *</label>
                    <input type="text" class="form-control @error('nom_client') is-invalid @enderror" 
                           id="nom_client" name="nom_client" value="{{ old('nom_client') }}" required>
                    @error('nom_client')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="motif_sortie" class="form-label">Motif de sortie *</label>
                    <select class="form-select @error('motif_sortie') is-invalid @enderror" id="motif_sortie" name="motif_sortie" required>
                        <option value="">-- Sélectionner un motif --</option>
                        <option value="Entreprise" {{ old('motif_sortie') == 'Entreprise' ? 'selected' : '' }}>Entreprise</option>
                        <option value="Client particulier" {{ old('motif_sortie') == 'Client particulier' ? 'selected' : '' }}>Client particulier</option>
                        <option value="Retour fournisseur" {{ old('motif_sortie') == 'Retour fournisseur' ? 'selected' : '' }}>Retour fournisseur</option>
                        <option value="Défectueux" {{ old('motif_sortie') == 'Défectueux' ? 'selected' : '' }}>Produit défectueux</option>
                        <option value="Autre" {{ old('motif_sortie') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    <div id="autre_motif_container" class="mt-2 d-none">
                        <input type="text" class="form-control" id="autre_motif" name="autre_motif" placeholder="Précisez le motif" value="{{ old('autre_motif') }}">
                    </div>
                    @error('motif_sortie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer la sortie</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produitSelect = document.getElementById('produit_id');
    const quantiteInput = document.getElementById('quantite');
    const stockAvailable = document.getElementById('stock-available');
    const motifSelect = document.getElementById('motif_sortie');
    const autreMotifContainer = document.getElementById('autre_motif_container');
    const autreMotifInput = document.getElementById('autre_motif');

    function updateStockAvailable() {
        if (produitSelect.selectedIndex > 0) {
            const selectedOption = produitSelect.options[produitSelect.selectedIndex];
            const maxQuantite = parseInt(selectedOption.dataset.quantite);
            quantiteInput.max = maxQuantite;
            stockAvailable.textContent = `Stock disponible: ${maxQuantite} unité(s)`;
            if (parseInt(quantiteInput.value) > maxQuantite) {
                quantiteInput.value = maxQuantite;
            }
        } else {
            stockAvailable.textContent = '';
        }
    }

    produitSelect.addEventListener('change', updateStockAvailable);
    updateStockAvailable();

    // Gestion du champ "Autre motif"
    motifSelect.addEventListener('change', function() {
        if (this.value === 'Autre') {
            autreMotifContainer.classList.remove('d-none');
        } else {
            autreMotifContainer.classList.add('d-none');
        }
    });

    // Déclencher l'événement change au chargement si "Autre" est sélectionné
    if (motifSelect.value === 'Autre') {
        motifSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection