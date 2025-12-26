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

    .stat-card.primary::before { background: var(--primary-color); }
    .stat-card.success::before { background: var(--success-color); }
    .stat-card.warning::before { background: var(--warning-color); }
    .stat-card.danger::before { background: var(--danger-color); }

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

    .stat-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary-color); }
    .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
    .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

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

    .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
    .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
    .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }
    .badge-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary-color); }

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
                
                
                <a href="{{ route('credits.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle"></i>
                    Nouveau Crédit
                </a>
                
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    @if(!$credits->isEmpty())
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
                    <input type="text"
                        id="search-input"
                        class="form-control"
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="m-0 fw-bold text-dark">
                <i class="fas fa-table me-2 text-primary"></i>
                Liste des crédits
            </h6>
            <div id="loading-indicator" class="d-none">
                <div class="loading-spinner">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <span>Recherche...</span>
                </div>
            </div>
        </div>

        @if($credits->isEmpty())
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
                        <tr>
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
            @if(method_exists($credits, 'links'))
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Affichage de {{ $credits->firstItem() }} à {{ $credits->lastItem() }} sur {{ $credits->total() }} crédits
                </div>
                <div>
                    {{ $credits->links() }}
                </div>
            </div>
            @endif
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const clearSearch = document.getElementById('clear-search');
    const tableBody = document.getElementById('credits-table-body');
    const loadingIndicator = document.getElementById('loading-indicator');
    const searchResultsCount = document.getElementById('search-results-count');
    const resultsCount = document.getElementById('results-count');

    let searchTimeout;

    // Recherche instantanée
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Bouton de réinitialisation
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        performSearch('');
        searchInput.focus();
    });

    function performSearch(query) {
        loadingIndicator.classList.remove('d-none');

        fetch(`{{ route('credits.search') }}?search=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                tableBody.innerHTML = data.html;

                if (query.length > 0) {
                    resultsCount.textContent = data.count;
                    searchResultsCount.classList.remove('d-none');
                } else {
                    searchResultsCount.classList.add('d-none');
                }

                loadingIndicator.classList.add('d-none');
            })
            .catch(error => {
                console.error('Erreur lors de la recherche:', error);
                loadingIndicator.classList.add('d-none');

                tableBody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                <h5 class="text-danger">Erreur!</h5>
                                <p class="text-muted">Impossible de charger les résultats. Veuillez réessayer.</p>
                            </div>
                        </td>
                    </tr>
                `;
            });
    }

    // Recherche au chargement si paramètre dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam) {
        searchInput.value = searchParam;
        performSearch(searchParam);
    }
});

// Message de confirmation après actions
@if(session('success'))
document.addEventListener('DOMContentLoaded', function() {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert-success-toast';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="stat-icon success me-3" style="width: 40px; height: 40px; font-size: 1rem; margin-bottom: 0;">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <div class="fw-bold">Succès!</div>
                <div class="text-muted small">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close ms-3" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
});
@endif
</script>
@endsection