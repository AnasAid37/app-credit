@extends('layouts.app')

@section('title', 'Détails du produit')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Détails du produit</h1>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Informations du produit</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Prix:</th>
                            <td>{{ number_format($product->price, 2) }} €</td>
                        </tr>
                        <tr>
                            <th>Taille:</th>
                            <td>{{ $product->taille }}</td>
                        </tr>
                        <tr>
                            <th>Marque:</th>
                            <td>{{ $product->marque ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Quantité:</th>
                            <td>{{ $product->quantite }}</td>
                        </tr>
                        <tr>
                            <th>Seuil d'alerte:</th>
                            <td>{{ $product->seuil_alerte }}</td>
                        </tr>
                        <tr>
                            <th>État:</th>
                            <td>
                                <span class="badge bg-{{ $product->stock_status }}">
                                    {{ $product->stock_status_text }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Retour à la liste</a>
                <div>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Modifier</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection