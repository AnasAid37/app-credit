@extends('layouts.app')

@section('title', 'Alertes de stock')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Alertes de stock</h1>

    <div class="card">
        <div class="card-body">
            @if($alertProducts->isEmpty())
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> Aucun produit en alerte actuellement.
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ $alertProducts->count() }} produit(s) en alerte de stock.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Prix</th>
                                <th>Taille</th>
                                <th>Marque</th>
                                <th>Quantité</th>
                                <th>Seuil d'alerte</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alertProducts as $product)
                                <tr class="stock-{{ $product->stock_status }}">
                                    <td>{{ number_format($product->price, 2) }} €</td>
                                    <td>{{ $product->taille }}</td>
                                    <td>{{ $product->marque ?? 'N/A' }}</td>
                                    <td><strong>{{ $product->quantite }}</strong></td>
                                    <td>{{ $product->seuil_alerte }}</td>
                                    <td>
                                        @if($product->isOutOfStock())
                                            <span class="badge bg-danger">Épuisé</span>
                                        @else
                                            <span class="badge bg-warning">Faible</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection