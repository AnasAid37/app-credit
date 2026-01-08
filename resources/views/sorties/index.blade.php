@extends('layouts.app')

@section('title', 'Historique des sorties')

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
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
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
            padding: 1.5rem;
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

        .stat-card.primary::before {
            background: var(--primary-color);
        }

        .stat-card.success::before {
            background: var(--success-color);
        }

        .stat-card.info::before {
            background: var(--info-color);
        }

        .stat-card.warning::before {
            background: var(--warning-color);
        }

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

        .stat-icon.primary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .stat-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }

        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

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

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .btn-modern {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-modern-success {
            background: var(--success-color);
            color: white;
        }

        .btn-modern-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .btn-modern-outline {
            background: white;
            border: 2px solid #e2e8f0;
            color: #64748b;
        }

        .btn-modern-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

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

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-modern tfoot {
            background: #f8fafc;
            font-weight: 600;
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

        .badge-primary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .badge-dark {
            background: rgba(30, 41, 59, 0.1);
            color: var(--dark-color);
        }

        .badge-secondary {
            background: rgba(100, 116, 139, 0.1);
            color: #64748b;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .empty-state h5 {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .empty-state p {
            color: #64748b;
            margin-bottom: 2rem;
        }

        .filter-badge {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination {
            gap: 0.5rem;
        }

        nav[role="navigation"],
        .pagination:not(.pagination-modern) {
            display: none !important;
        }

        /* Pagination Wrapper */
        .pagination-wrapper-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-info-modern {
            display: flex;
            align-items: center;
            color: #64748b;
            font-size: 0.875rem;
        }

        .pagination-info-modern i {
            color: var(--primary-color);
        }

        .pagination-info-modern strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Pagination moderne */
        .pagination-modern {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination-modern li {
            display: flex;
        }

        .pagination-modern li a,
        .pagination-modern li span {
            min-width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            background: white;
            color: #64748b;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .pagination-modern li a:hover {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        }

        .pagination-modern li.active span {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-color: var(--primary-color);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);
        }

        .pagination-modern li.disabled span {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #cbd5e1;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination-modern li i {
            font-size: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pagination-wrapper-modern {
                flex-direction: column;
                gap: 1rem;
            }

            .pagination-info-modern {
                width: 100%;
                justify-content: center;
                text-align: center;
            }

            .pagination-modern {
                width: 100%;
                justify-content: center;
            }

            .pagination-modern li a,
            .pagination-modern li span {
                min-width: 36px;
                height: 36px;
                padding: 0.375rem 0.5rem;
                font-size: 0.8125rem;
            }
        }

        @media (max-width: 480px) {

            .pagination-modern li a,
            .pagination-modern li span {
                min-width: 32px;
                height: 32px;
                padding: 0.25rem 0.375rem;
                font-size: 0.75rem;
            }
        }

        .page-link {
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            color: var(--dark-color);
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-color: var(--primary-color);
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

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn-view {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .action-btn-view:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4);
        }

        .table-modern thead th:last-child,
        .table-modern tbody td:last-child {
            text-align: center;
            width: 80px;
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
                        <i class="fas fa-arrow-circle-down me-2"></i>
                        Historique des sorties
                    </h1>
                    <p class="mb-0 opacity-90">Consulter l'historique complet des sorties de stock</p>
                </div>
                <a href="{{ route('sorties.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle"></i>
                    Nouvelle sortie
                </a>
            </div>
        </div>

        <!-- Statistiques rapides -->
        @if (!$sorties->isEmpty())
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card primary">
                        <div class="stat-icon primary">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-value">{{ $sorties->total() }}</div>
                        <div class="stat-label">Total Sorties</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card success">
                        <div class="stat-icon success">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-value">{{ $sorties->sum('quantite') }}</div>
                        <div class="stat-label">Unités vendues</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card info">
                        <div class="stat-icon info">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-value">{{ number_format($sorties->sum('prix_total'), 0) }} DH</div>
                        <div class="stat-label">Chiffre d'affaires</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card warning">
                        <div class="stat-icon warning">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="stat-value">
                            {{ $sorties->count() > 0 ? number_format($sorties->sum('prix_total') / $sorties->count(), 0) : '0' }}
                            DH</div>
                        <div class="stat-label">Prix moyen</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filtres de recherche -->
        <div class="modern-card">
            <h6 class="mb-4 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-filter text-primary"></i>
                Filtres de recherche
            </h6>

            <form method="GET" action="{{ route('sorties.index') }}" id="search-form">
                <div class="row g-3">
                    <!-- Date début -->
                    <div class="col-lg-3 col-md-6">
                        <label for="date_debut" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i> Date début
                        </label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut"
                            value="{{ request('date_debut') }}">
                    </div>

                    <!-- Date fin -->
                    <div class="col-lg-3 col-md-6">
                        <label for="date_fin" class="form-label">
                            <i class="fas fa-calendar-check me-1"></i> Date fin
                        </label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin"
                            value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="nom_client" class="form-label">
                            <i class="fas fa-user me-1"></i> Nom du client
                        </label>
                        <input type="text" class="form-control" id="nom_client" name="nom_client"
                            placeholder="Rechercher un client..." value="{{ request('nom_client') }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="category_id" class="form-label">
                            <i class="fas fa-folder me-1"></i> Catégorie
                        </label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">-- Toutes les catégories --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    <i class="{{ $cat->icone }}"></i> {{ $cat->nom }}
                                    ({{ $cat->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Motif de sortie -->
                    <div class="col-lg-2 col-md-6">
                        <label for="motif_sortie" class="form-label">
                            <i class="fas fa-tag me-1"></i> Motif de sortie
                        </label>
                        <select class="form-select" id="motif_sortie" name="motif_sortie">
                            <option value="">-- Tous les motifs --</option>
                            <option value="Entreprise" {{ request('motif_sortie') == 'Entreprise' ? 'selected' : '' }}>
                                Entreprise</option>
                            <option value="Client particulier"
                                {{ request('motif_sortie') == 'Client particulier' ? 'selected' : '' }}>Client particulier
                            </option>
                            <option value="Retour fournisseur"
                                {{ request('motif_sortie') == 'Retour fournisseur' ? 'selected' : '' }}>Retour fournisseur
                            </option>
                            <option value="Défectueux" {{ request('motif_sortie') == 'Défectueux' ? 'selected' : '' }}>
                                Produit défectueux</option>
                            <option value="Autre" {{ request('motif_sortie') == 'Autre' ? 'selected' : '' }}>Autre
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between align-items-center pt-4 mt-3 border-top">
                    <div>
                        @if (request()->hasAny(['date_debut', 'date_fin', 'nom_client', 'motif_sortie']))
                            <span class="filter-badge">
                                <i class="fas fa-filter"></i>
                                Filtres actifs
                            </span>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-modern-outline" id="reset-search">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-modern-primary">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des sorties -->
        <div class="modern-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h6 class="m-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-table text-primary"></i>
                    Liste des sorties
                </h6>
                @if (!$sorties->isEmpty())
                    <button class="btn btn-modern-success btn-sm" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Exporter
                    </button>
                @endif

            </div>

            @if ($sorties->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-arrow-circle-down"></i>
                    <h5>Aucune sortie de stock trouvée</h5>
                    <p class="text-muted">
                        @if (request()->hasAny(['date_debut', 'date_fin', 'nom_client', 'motif_sortie']))
                            Aucun résultat ne correspond aux critères sélectionnés.
                        @else
                            Aucune sortie de stock enregistrée pour le moment.
                        @endif
                    </p>
                    <a href="{{ route('sorties.create') }}" class="btn btn-modern-primary">
                        <i class="fas fa-plus-circle"></i> Enregistrer une sortie
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-modern" id="sortiesTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar me-1"></i> Date</th>
                                <th><i class="fas fa-box me-1"></i> Produit</th>
                                <th><i class="fas fa-tag me-1"></i> Prix unitaire</th>
                                <th><i class="fas fa-cubes me-1"></i> Quantité</th>
                                <th><i class="fas fa-credit-card me-1"></i> Paiement</th>
                                <th><i class="fas fa-money-bill-wave me-1"></i> Prix total</th>
                                <th><i class="fas fa-user me-1"></i> Client</th>
                                <th><i class="fas fa-clipboard me-1"></i> Motif</th>
                                <th><i class="fas fa-user-tie me-1"></i> Utilisateur</th>
                                <th><i class="fas fa-cog me-1"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sorties as $sortie)
                                <tr>
                                    <!-- Date -->
                                    <td>
                                        <span class="badge-modern badge-secondary">
                                            {{ $sortie->created_at->format('d/m/Y') }}
                                        </span>
                                        <small class="d-block text-muted mt-1">
                                            {{ $sortie->created_at->format('H:i') }}
                                        </small>
                                    </td>

                                    <!-- Produit -->
                                    <td>
                                        <div class="fw-bold text-dark">{{ $sortie->product->marque ?? 'N/A' }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-ruler me-1"></i>{{ $sortie->product->taille }}
                                        </small>
                                    </td>

                                    <!-- Prix unitaire -->
                                    <td class="text-primary fw-bold">
                                        {{ number_format($sortie->product->price, 2) }} DH
                                    </td>

                                    <!-- Quantité -->
                                    <td>
                                        <span class="badge-modern badge-dark">
                                            {{ $sortie->quantite }}
                                        </span>
                                    </td>

                                    <!-- ✅ Paiement (العمود الناقص) -->
                                    <td>
                                        @if ($sortie->payment_mode === 'cash')
                                            <span class="badge-modern badge-success">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Comptant
                                            </span>
                                        @else
                                            <span class="badge-modern badge-warning" data-bs-toggle="tooltip"
                                                title="Voir le crédit">
                                                <i class="fas fa-hand-holding-usd"></i>
                                                Crédit
                                                @if ($sortie->credit)
                                                    <a href="{{ route('credits.show', $sortie->credit_id) }}"
                                                        class="text-white ms-1" style="text-decoration: underline;">
                                                        #{{ $sortie->credit_id }}
                                                    </a>
                                                @endif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Prix total -->
                                    <td class="text-success fw-bold">
                                        {{ number_format($sortie->total_price, 2) }} DH
                                    </td>

                                    <!-- Client -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-user-circle text-muted"></i>
                                            <span>{{ $sortie->nom_client }}</span>
                                        </div>
                                    </td>

                                    <!-- Motif -->
                                    <td>
                                        @php
                                            $motifColors = [
                                                'Entreprise' => 'primary',
                                                'Client particulier' => 'info',
                                                'Retour fournisseur' => 'warning',
                                                'Défectueux' => 'danger',
                                                'Autre' => 'secondary',
                                            ];
                                            $color = $motifColors[$sortie->motif_sortie] ?? 'secondary';
                                        @endphp
                                        <span class="badge-modern badge-{{ $color }}">
                                            {{ $sortie->motif_sortie }}
                                        </span>
                                    </td>

                                    <!-- Utilisateur -->
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-user-check me-1"></i>
                                            {{ $sortie->user->nom ?? ($sortie->user->name ?? 'N/A') }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('sorties.show', $sortie->id) }}"
                                            class="action-btn action-btn-view" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td>
                                    <span class="badge-modern badge-dark">
                                        {{ $sorties->sum('quantite') }}
                                    </span>
                                </td>
                                <td></td> {{-- عمود Paiement --}}
                                <td class="text-success fw-bold">
                                    {{ number_format($sorties->sum('total_price'), 2) }} DH
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <!-- Pagination -->
                @if (method_exists($sorties, 'links') && $sorties->hasPages())
                    <div class="pagination-wrapper-modern">
                        <div class="pagination-info-modern">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>
                                Affichage de
                                <strong>{{ $sorties->firstItem() }}</strong>
                                à
                                <strong>{{ $sorties->lastItem() }}</strong>
                                sur
                                <strong>{{ $sorties->total() }}</strong>
                                sorties
                            </span>
                        </div>

                        <nav>
                            <ul class="pagination-modern">

                                {{-- Précédent --}}
                                @if ($sorties->onFirstPage())
                                    <li class="disabled">
                                        <span><i class="fas fa-chevron-left"></i></span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $sorties->previousPageUrl() }}">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pages --}}
                                @php
                                    $currentPage = $sorties->currentPage();
                                    $lastPage = $sorties->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp

                                @if ($start > 1)
                                    <li>
                                        <a href="{{ $sorties->url(1) }}">1</a>
                                    </li>
                                    @if ($start > 2)
                                        <li class="disabled"><span>...</span></li>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    @if ($i == $currentPage)
                                        <li class="active">
                                            <span>{{ $i }}</span>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $sorties->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                @if ($end < $lastPage)
                                    @if ($end < $lastPage - 1)
                                        <li class="disabled"><span>...</span></li>
                                    @endif
                                    <li>
                                        <a href="{{ $sorties->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                @endif

                                {{-- Suivant --}}
                                @if ($sorties->hasMorePages())
                                    <li>
                                        <a href="{{ $sorties->nextPageUrl() }}">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="disabled">
                                        <span><i class="fas fa-chevron-right"></i></span>
                                    </li>
                                @endif

                            </ul>
                        </nav>
                    </div>
                @endif

            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Réinitialiser les filtres
            document.getElementById('reset-search').addEventListener('click', function() {
                window.location.href = '{{ route('sorties.index') }}';
            });
        });

        // Fonction d'export vers Excel
        function exportToExcel() {
            const table = document.getElementById('sortiesTable');
            let csv = [];

            // En-têtes
            const headers = [];
            table.querySelectorAll('thead th').forEach(th => {
                headers.push(th.textContent.trim().replace(/\s+/g, ' '));
            });
            csv.push(headers.join(','));

            // Données
            table.querySelectorAll('tbody tr').forEach(tr => {
                const row = [];
                tr.querySelectorAll('td').forEach(td => {
                    const text = td.textContent.trim().replace(/\s+/g, ' ');
                    row.push('"' + text.replace(/"/g, '""') + '"');
                });
                csv.push(row.join(','));
            });

            // Télécharger
            const csvContent = '\uFEFF' + csv.join('\n'); // UTF-8 BOM
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'sorties_' + new Date().toISOString().split('T')[0] + '.csv';
            link.click();
        }

        // Message de succès après action
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'position-fixed top-0 end-0 p-3';
                alertDiv.style.zIndex = '9999';
                alertDiv.innerHTML = `
        <div class="modern-card" style="min-width: 300px; animation: slideInRight 0.3s ease;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon success" style="width: 50px; height: 50px; margin: 0;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold text-dark mb-1">Succès!</div>
                    <div class="text-muted small">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.parentElement.remove()"></button>
            </div>
        </div>
    `;
                document.body.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => alertDiv.remove(), 300);
                }, 5000);
            });
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });

            const style = document.createElement('style');
            style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
            document.head.appendChild(style);
        @endif
    </script>
@endsection
