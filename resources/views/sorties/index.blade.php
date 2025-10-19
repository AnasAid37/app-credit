@extends('layouts.app')

@section('title', 'Historique des sorties')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Historique des sorties de stock</h1>

    <!-- Filtres de recherche -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filtres de recherche</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="" id="search-form">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="date_debut" class="form-label">Date début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut"
                            value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_fin" class="form-label">Date fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin"
                            value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="nom_client" class="form-label">Nom du client</label>
                        <input type="text" class="form-control" id="nom_client" name="nom_client"
                            value="{{ request('nom_client') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="motif_sortie" class="form-label">Motif de sortie</label>
                        <input type="text" class="form-control" id="motif_sortie" name="motif_sortie"
                            value="{{ request('motif_sortie') }}">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <button type="button" class="btn btn-secondary" id="reset-search">Réinitialiser</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des sorties -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des sorties</h5>
        </div>
        <div class="card-body">
            @if($sorties->isEmpty())
                <div class="alert alert-info">
                    Aucune sortie de stock trouvée pour les critères sélectionnés.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Prix total</th>
                                <th>Client</th>
                                <th>Motif</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sorties as $sortie)
                                <tr>
                                    <td>{{ $sortie->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $sortie->product->marque ?? 'N/A' }} ({{ $sortie->product->taille }})</td>
                                    <td>{{ number_format($sortie->product->price, 2) }} €</td>
                                    <td>{{ $sortie->quantite }}</td>
                                    <td>{{ number_format($sortie->prix_total, 2) }} €</td>
                                    <td>{{ $sortie->nom_client }}</td>
                                    <td>{{ $sortie->motif_sortie }}</td>
                                    <td>{{ $sortie->user->nom }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $sorties->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Réinitialiser les filtres
        document.getElementById('reset-search').addEventListener('click', function() {
            document.getElementById('date_debut').value = '';
            document.getElementById('date_fin').value = '';
            document.getElementById('nom_client').value = '';
            document.getElementById('motif_sortie').value = '';
            document.getElementById('search-form').submit();
        });
    });
</script>
@endsection