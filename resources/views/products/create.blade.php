@extends('layouts.app')

@section('title', 'Ajouter un produit')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Ajouter un produit</h1>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="price" class="form-label">Prix du produit *</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="taille" class="form-label">Taille *</label>
                    <input type="text" class="form-control @error('taille') is-invalid @enderror" 
                           id="taille" name="taille" placeholder="Ex: 205/55 R16" value="{{ old('taille') }}" required>
                    @error('taille')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="marque" class="form-label">Marque</label>
                    <input type="text" class="form-control @error('marque') is-invalid @enderror" 
                           id="marque" name="marque" placeholder="Ex: Michelin" value="{{ old('marque') }}">
                    @error('marque')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="quantite" class="form-label">Quantité initiale</label>
                    <input type="number" class="form-control @error('quantite') is-invalid @enderror" 
                           id="quantite" name="quantite" min="0" value="{{ old('quantite', 0) }}">
                    @error('quantite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="seuil_alerte" class="form-label">Seuil d'alerte</label>
                    <input type="number" class="form-control @error('seuil_alerte') is-invalid @enderror" 
                           id="seuil_alerte" name="seuil_alerte" min="1" value="{{ old('seuil_alerte', 5) }}">
                    <div class="form-text">Une alerte sera générée lorsque la quantité sera inférieure ou égale à cette valeur.</div>
                    @error('seuil_alerte')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Ajouter le produit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection