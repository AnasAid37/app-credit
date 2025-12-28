@extends('layouts.app')

@section('title', 'Gestion des Crédits')

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
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
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

        .stat-card.warning::before {
            background: var(--warning-color);
        }

        .stat-card.danger::before {
            background: var(--danger-color);
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

        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stat-icon.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
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

        .search-box {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .search-box h6 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .input-group-text {
            background: var(--light-bg);
            border: 2px solid #e2e8f0;
            border-right: none;
            color: #64748b;
        }

        .input-group .form-control {
            border-left: none;
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

        .badge-modern {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .badge-primary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .client-avatar {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .action-btn-group {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn-view {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }

        .action-btn-view:hover {
            background: var(--info-color);
            color: white;
            transform: translateY(-2px);
        }

        .action-btn-edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .delete-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--danger-color);
            margin: 0;
        }

        .checkbox-cell {
            width: 50px;
            text-align: center;
            padding: 1rem 0.5rem !important;
        }

        tr.selected-row {
            background-color: rgba(239, 68, 68, 0.05) !important;
        }

        .bulk-delete-mode tbody tr:hover {
            background: rgba(239, 68, 68, 0.08) !important;
        }

        #bulk-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.2s ease;
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

        .modal-content-custom {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: var(--card-hover-shadow);
            animation: scaleIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(-20px);
            }
        }

        .modal-icon-danger {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1.5rem;
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
            cursor: pointer;
        }

        .btn-danger-custom {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger-custom:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4);
        }

        .action-btn-edit:hover {
            background: var(--warning-color);
            color: white;
            transform: translateY(-2px);
        }

        .action-btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .action-btn-delete:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-2px);
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

        .loading-spinner {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            font-size: 0.875rem;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        .search-results-badge {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success-toast {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 9999;
            min-width: 300px;
            background: white;
            border-radius: 0.75rem;
            box-shadow: var(--card-hover-shadow);
            padding: 1rem 1.5rem;
            border-left: 4px solid var(--success-color);
            animation: slideInRight 0.3s ease;
        }

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

        .pagination {
            gap: 0.5rem;
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

            .action-btn-group {
                flex-direction: column;
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
                    <h1 class="mb-1">Gestion des Crédits</h1>
                    <p class="mb-0 opacity-90">
                        <i class="fas fa-hand-holding-usd me-2"></i>
                        Gérer les crédits clients
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">

                    <a href="{{ route('credits.export') }}" class="btn btn-light">
                        <i class="fas fa-file-export"></i>
                        Exporter
                    </a>
                    <a href="{{ route('credits.import') }}" class="btn btn-light">
                        <i class="fas fa-file-import"></i>
                        Importer


                    </a>
                    <a href="{{ route('credits.template') }}" class="btn btn-secondary"
                        title="Télécharger le modèle CSV">
                        <i class="fas fa-download"></i>
                        Modèle
                    </a>


                    <a href="{{ route('credits.create') }}" class="btn btn-light">
                        <i class="fas fa-plus-circle"></i>
                        Nouveau Crédit
                    </a>

                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        @if (!$credits->isEmpty())
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card primary">
                        <div class="stat-icon primary">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-value">{{ $credits->count() }}</div>
                        <div class="stat-label">Total Crédits</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card success">
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value">{{ $credits->where('status', 'paid')->count() }}</div>
                        <div class="stat-label">Crédits Payés</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card warning">
                        <div class="stat-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value">{{ $credits->where('status', 'pending')->count() }}</div>
                        <div class="stat-label">En Cours</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card danger">
                        <div class="stat-icon danger">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-value">{{ number_format($credits->sum('remaining_amount'), 0) }} DH</div>
                        <div class="stat-label">Montant Restant</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Barre de recherche -->
        <div class="search-box">
            <h6>
                <i class="fas fa-search text-primary"></i>
                Recherche
            </h6>
            <div class="row">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="search-input" class="form-control"
                            placeholder="Rechercher par nom du client, téléphone ou raison...">
                        <button class="btn btn-modern-outline" type="button" id="clear-search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="search-results-count" class="mt-2 d-none">
                        <span class="search-results-badge">
                            <i class="fas fa-info-circle"></i>
                            <span id="results-count">0</span> résultat(s) trouvé(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des crédits -->
        <div class="modern-card">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <h6 class="m-0 fw-bold text-dark">
                    <i class="fas fa-table me-2 text-primary"></i>
                    Liste des crédits
                </h6>

                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <!-- زر تفعيل وضع الحذف -->
                    @if (!$credits->isEmpty())
                        <button id="toggle-delete-mode" class="btn btn-modern-outline" type="button">
                            <i class="fas fa-trash-alt"></i>
                            <span id="delete-mode-text">Sélectionner</span>
                        </button>
                    @endif

                    <!-- أزرار الإجراءات الجماعية -->
                    <div id="bulk-actions" class="d-none">
                        <button id="select-all" class="btn btn-modern-outline btn-sm" type="button">
                            <i class="fas fa-check-double"></i>
                            Tout
                        </button>
                        <button id="deselect-all" class="btn btn-modern-outline btn-sm" type="button">
                            <i class="fas fa-times"></i>
                            Rien
                        </button>
                        <button id="delete-selected" class="btn btn-modern btn-danger-custom" type="button" disabled>
                            <i class="fas fa-trash"></i>
                            Supprimer (<span id="selected-count">0</span>)
                        </button>
                    </div>

                    <div id="loading-indicator" class="d-none">
                        <div class="loading-spinner">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <span>Recherche...</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($credits->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <h5>Aucun crédit trouvé</h5>
                    <p class="text-muted">Commencez par créer un nouveau crédit.</p>
                    <a href="{{ route('credits.create') }}" class="btn btn-modern-primary">
                        <i class="fas fa-plus-circle"></i>
                        Nouveau crédit
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-modern" id="credits-table">
                        <thead>
                            <tr id="table-header">
                                <th><i class="fas fa-user me-1"></i> Client</th>
                                <th><i class="fas fa-phone me-1"></i> Téléphone</th>
                                <th><i class="fas fa-money-bill-wave me-1"></i> Montant</th>
                                <th><i class="fas fa-hand-holding-usd me-1"></i> Payé</th>
                                <th><i class="fas fa-wallet me-1"></i> Restant</th>
                                <th><i class="fas fa-clipboard me-1"></i> Raison</th>
                                <th><i class="fas fa-info-circle me-1"></i> Statut</th>
                                <th><i class="fas fa-calendar me-1"></i> Date</th>
                                <th class="text-center"><i class="fas fa-cog me-1"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody id="credits-table-body">
                            @include('credits.partials.credit-rows', ['credits' => $credits])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if (method_exists($credits, 'links') && $credits->hasPages())
                    <div class="pagination-wrapper-modern">
                        <div class="pagination-info-modern">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>
                                Affichage de <strong>{{ $credits->firstItem() }}</strong>
                                à <strong>{{ $credits->lastItem() }}</strong>
                                sur <strong>{{ $credits->total() }}</strong> crédits
                            </span>
                        </div>

                        <nav>
                            <ul class="pagination-modern">
                                {{-- Bouton Précédent --}}
                                @if ($credits->onFirstPage())
                                    <li class="disabled">
                                        <span><i class="fas fa-chevron-left"></i></span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $credits->previousPageUrl() }}">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Numéros de pages --}}
                                @php
                                    $currentPage = $credits->currentPage();
                                    $lastPage = $credits->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp

                                @if ($start > 1)
                                    <li>
                                        <a href="{{ $credits->url(1) }}">1</a>
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
                                            <a href="{{ $credits->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                @if ($end < $lastPage)
                                    @if ($end < $lastPage - 1)
                                        <li class="disabled"><span>...</span></li>
                                    @endif
                                    <li>
                                        <a href="{{ $credits->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                @endif

                                {{-- Bouton Suivant --}}
                                @if ($credits->hasMorePages())
                                    <li>
                                        <a href="{{ $credits->nextPageUrl() }}">
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
            const toggleDeleteBtn = document.getElementById('toggle-delete-mode');
            if (!toggleDeleteBtn) return;

            const deleteModeText = document.getElementById('delete-mode-text');
            const bulkActions = document.getElementById('bulk-actions');
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');
            const deleteSelectedBtn = document.getElementById('delete-selected');
            const selectedCountSpan = document.getElementById('selected-count');
            const creditsTable = document.getElementById('credits-table');
            const tableBody = document.getElementById('credits-table-body');
            const tableHeader = document.getElementById('table-header');

            let deleteMode = false;
            let selectedIds = new Set();

            // تبديل وضع الحذف
            toggleDeleteBtn.addEventListener('click', function() {
                deleteMode = !deleteMode;

                if (deleteMode) {
                    activateDeleteMode();
                } else {
                    deactivateDeleteMode();
                }
            });

            function activateDeleteMode() {
                deleteModeText.textContent = 'Annuler';
                toggleDeleteBtn.classList.remove('btn-modern-outline');
                toggleDeleteBtn.classList.add('btn-modern-success');
                toggleDeleteBtn.style.background = 'var(--danger-color)';
                toggleDeleteBtn.style.color = 'white';
                bulkActions.classList.remove('d-none');
                creditsTable.classList.add('bulk-delete-mode');

                // إضافة عمود checkbox في الـ header
                const checkboxHeader = document.createElement('th');
                checkboxHeader.className = 'checkbox-cell';
                checkboxHeader.innerHTML = `
            <input type="checkbox" id="select-all-checkbox" class="delete-checkbox" title="Tout sélectionner">
        `;
                tableHeader.insertBefore(checkboxHeader, tableHeader.firstChild);

                // إضافة checkboxes لكل صف
                const rows = tableBody.querySelectorAll('tr[data-credit-id]');
                rows.forEach(row => {
                    const creditId = row.dataset.creditId;
                    if (creditId) {
                        const checkboxCell = document.createElement('td');
                        checkboxCell.className = 'checkbox-cell';
                        checkboxCell.innerHTML = `
                    <input type="checkbox" class="delete-checkbox row-checkbox" data-id="${creditId}">
                `;
                        row.insertBefore(checkboxCell, row.firstChild);
                    }
                });

                // معالج اختيار الكل من الـ header
                const selectAllCheckbox = document.getElementById('select-all-checkbox');
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        const checkboxes = document.querySelectorAll('.row-checkbox');
                        checkboxes.forEach(cb => {
                            cb.checked = this.checked;
                            updateRowSelection(cb);
                        });
                        updateSelectedCount();
                    });
                }

                // معالج اختيار الصفوف الفردية
                document.querySelectorAll('.row-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateRowSelection(this);
                        updateSelectedCount();
                        updateSelectAllCheckbox();
                    });
                });
            }

            function deactivateDeleteMode() {
                deleteModeText.textContent = 'Sélectionner';
                toggleDeleteBtn.classList.add('btn-modern-outline');
                toggleDeleteBtn.classList.remove('btn-modern-success');
                toggleDeleteBtn.style.background = '';
                toggleDeleteBtn.style.color = '';
                bulkActions.classList.add('d-none');
                creditsTable.classList.remove('bulk-delete-mode');

                // إزالة عمود checkbox من الـ header
                const checkboxHeader = tableHeader.querySelector('.checkbox-cell');
                if (checkboxHeader) checkboxHeader.remove();

                // إزالة checkboxes من الصفوف
                const checkboxCells = tableBody.querySelectorAll('.checkbox-cell');
                checkboxCells.forEach(cell => cell.remove());

                // مسح الاختيارات
                selectedIds.clear();
                document.querySelectorAll('.selected-row').forEach(row => {
                    row.classList.remove('selected-row');
                });
                updateSelectedCount();
            }

            function updateRowSelection(checkbox) {
                const row = checkbox.closest('tr');
                const creditId = checkbox.dataset.id;

                if (checkbox.checked) {
                    selectedIds.add(creditId);
                    row.classList.add('selected-row');
                } else {
                    selectedIds.delete(creditId);
                    row.classList.remove('selected-row');
                }
            }

            function updateSelectAllCheckbox() {
                const selectAllCheckbox = document.getElementById('select-all-checkbox');
                if (!selectAllCheckbox) return;

                const allCheckboxes = document.querySelectorAll('.row-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');

                selectAllCheckbox.checked = allCheckboxes.length > 0 && allCheckboxes.length === checkedCheckboxes
                    .length;
                selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length <
                    allCheckboxes.length;
            }

            function updateSelectedCount() {
                const count = selectedIds.size;
                selectedCountSpan.textContent = count;
                deleteSelectedBtn.disabled = count === 0;

                if (count === 0) {
                    deleteSelectedBtn.style.opacity = '0.6';
                    deleteSelectedBtn.style.cursor = 'not-allowed';
                } else {
                    deleteSelectedBtn.style.opacity = '1';
                    deleteSelectedBtn.style.cursor = 'pointer';
                }
            }

            // زر اختيار الكل
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    const selectAllCheckbox = document.getElementById('select-all-checkbox');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = true;
                        selectAllCheckbox.dispatchEvent(new Event('change'));
                    }
                });
            }

            // زر إلغاء اختيار الكل
            if (deselectAllBtn) {
                deselectAllBtn.addEventListener('click', function() {
                    const selectAllCheckbox = document.getElementById('select-all-checkbox');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.dispatchEvent(new Event('change'));
                    }
                });
            }

            // زر الحذف
            if (deleteSelectedBtn) {
                deleteSelectedBtn.addEventListener('click', function() {
                    if (selectedIds.size === 0) return;
                    showDeleteConfirmation();
                });
            }

            function showDeleteConfirmation() {
                const modal = document.createElement('div');
                modal.className = 'modal-overlay';
                modal.innerHTML = `
            <div class="modal-content-custom">
                <div class="modal-icon-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4 class="text-center mb-3 fw-bold">Confirmer la suppression</h4>
                <p class="text-center text-muted mb-2">
                    Voulez-vous vraiment supprimer <strong class="text-danger">${selectedIds.size}</strong> crédit(s) sélectionné(s) ?
                </p>
                <div class="alert alert-danger d-flex align-items-center justify-content-center mb-4" style="background: rgba(239, 68, 68, 0.1); border: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span class="small">Cette action est irréversible</span>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-modern btn-modern-outline" id="cancel-delete">
                        <i class="fas fa-times"></i>
                        Annuler
                    </button>
                    <button class="btn btn-modern btn-danger-custom" id="confirm-delete">
                        <i class="fas fa-trash"></i>
                        Confirmer
                    </button>
                </div>
            </div>
        `;

                document.body.appendChild(modal);

                // إغلاق النافذة
                modal.querySelector('#cancel-delete').addEventListener('click', () => {
                    modal.remove();
                });

                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.remove();
                    }
                });

                // تأكيد الحذف
                modal.querySelector('#confirm-delete').addEventListener('click', () => {
                    performBulkDelete();
                    modal.remove();
                });
            }

            function performBulkDelete() {
                const idsArray = Array.from(selectedIds);

                // عرض مؤشر التحميل
                deleteSelectedBtn.disabled = true;
                deleteSelectedBtn.innerHTML = `
            <div class="spinner-border spinner-border-sm me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Suppression...
        `;

                fetch('{{ route('credits.bulk-delete') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            ids: idsArray
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // إزالة الصفوف المحذوفة بأنيميشن
                            idsArray.forEach(id => {
                                const row = tableBody.querySelector(`tr[data-credit-id="${id}"]`);
                                if (row) {
                                    row.style.animation = 'fadeOut 0.3s ease';
                                    setTimeout(() => row.remove(), 300);
                                }
                            });

                            // رسالة نجاح
                            showSuccessToast(`${data.count} crédit(s) supprimé(s) avec succès`);

                            // إعادة تعيين الوضع
                            setTimeout(() => {
                                selectedIds.clear();
                                deactivateDeleteMode();

                                // إعادة تحميل الصفحة لتحديث الإحصائيات والـ pagination
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }, 500);
                        } else {
                            showErrorToast(data.message || 'Erreur lors de la suppression');
                            // إعادة تفعيل الزر
                            deleteSelectedBtn.disabled = false;
                            deleteSelectedBtn.innerHTML = `
                    <i class="fas fa-trash"></i>
                    Supprimer (<span id="selected-count">${selectedIds.size}</span>)
                `;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showErrorToast('Erreur lors de la suppression');
                        // إعادة تفعيل الزر
                        deleteSelectedBtn.disabled = false;
                        deleteSelectedBtn.innerHTML = `
                <i class="fas fa-trash"></i>
                Supprimer (<span id="selected-count">${selectedIds.size}</span>)
            `;
                    });
            }

            function showSuccessToast(message) {
                const toast = document.createElement('div');
                toast.className = 'alert-success-toast';
                toast.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="stat-icon success me-3" style="width: 40px; height: 40px; font-size: 1rem; margin-bottom: 0;">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <div class="fw-bold">Succès!</div>
                    <div class="text-muted small">${message}</div>
                </div>
                <button type="button" class="btn-close ms-3" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 5000);
            }

            function showErrorToast(message) {
                const toast = document.createElement('div');
                toast.className = 'alert-success-toast';
                toast.style.borderLeftColor = 'var(--danger-color)';
                toast.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="stat-icon danger me-3" style="width: 40px; height: 40px; font-size: 1rem; margin-bottom: 0;">
                    <i class="fas fa-times"></i>
                </div>
                <div>
                    <div class="fw-bold">Erreur!</div>
                    <div class="text-muted small">${message}</div>
                </div>
                <button type="button" class="btn-close ms-3" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 5000);
            }
        });
    </script>
@endsection
