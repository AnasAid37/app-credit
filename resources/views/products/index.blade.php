@extends('layouts.app')

@section('title', 'Liste des stocks')

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
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
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

    .modern-card:hover {
        box-shadow: var(--card-hover-shadow);
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
        padding: 2rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
    }

    .search-box h6 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.625rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
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

    .badge-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary-color); }
    .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
    .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
    .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }
    .badge-secondary { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .badge-dark { background: rgba(30, 41, 59, 0.1); color: var(--dark-color); }

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
    }

    .action-btn-edit {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary-color);
    }

    .action-btn-edit:hover {
        background: var(--primary-color);
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
        background: white;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
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

    .modal-content {
        border: none;
        border-radius: 1rem;
        box-shadow: var(--card-hover-shadow);
    }

    .modal-header {
        border-bottom: 1px solid #f1f5f9;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 1.5rem;
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
                <h1 class="mb-1">Liste des Stocks</h1>
                <p class="mb-0 opacity-90">
                    <i class="fas fa-box me-2"></i>
                    Gérer vos produits en stock
                </p>
            </div>
            <a href="{{ route('products.create') }}" class="btn btn-light">
                <i class="fas fa-plus-circle me-2"></i>
                Ajouter un produit
            </a>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="search-box">
        <h6>
            <i class="fas fa-filter text-primary"></i>
            Recherche et Filtres
        </h6>
        <form action="{{ route('products.index') }}" method="GET" class="row g-3">
            <!-- Barre de recherche -->
            <div class="col-lg-6 col-md-12">
                <label class="form-label fw-500">Rechercher</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           placeholder="Prix, taille, marque..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-modern-primary" type="submit">
                        Rechercher
                    </button>
                </div>
            </div>

            <!-- Tri -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-500">Trier par</label>
                <select class="form-select" name="sort">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date d'ajout</option>
                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Prix</option>
                    <option value="taille" {{ request('sort') == 'taille' ? 'selected' : '' }}>Taille</option>
                    <option value="marque" {{ request('sort') == 'marque' ? 'selected' : '' }}>Marque</option>
                    <option value="quantite" {{ request('sort') == 'quantite' ? 'selected' : '' }}>Quantité</option>
                </select>
            </div>

            <!-- Ordre -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-500">Ordre</label>
                <div class="input-group">
                    <select class="form-select" name="order">
                        <option value="ASC" {{ request('order') == 'ASC' ? 'selected' : '' }}>Croissant</option>
                        <option value="DESC" {{ request('order') == 'DESC' ? 'selected' : '' }}>Décroissant</option>
                    </select>
                    <button class="btn btn-modern-outline" type="submit">
                        <i class="fas fa-sort"></i>
                    </button>
                </div>
            </div>

            @if(request('search') || request('sort') || request('order'))
            <div class="col-12">
                <a href="{{ route('products.index') }}" class="btn btn-modern-outline btn-sm">
                    <i class="fas fa-redo me-1"></i> Réinitialiser les filtres
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Statistiques rapides -->
    @if(!$products->isEmpty())
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary">
                <div class="stat-icon primary">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-value">{{ $products->total() }}</div>
                <div class="stat-label">Total Produits</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card success">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $products->where('stock_status', 'success')->count() }}</div>
                <div class="stat-label">En Stock</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-icon warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value">{{ $products->where('stock_status', 'warning')->count() }}</div>
                <div class="stat-label">Stock Faible</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card danger">
                <div class="stat-icon danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-value">{{ $products->where('stock_status', 'danger')->count() }}</div>
                <div class="stat-label">Rupture</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tableau des produits -->
    <div class="modern-card">
        @if($products->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h5>Aucun produit trouvé</h5>
                <p class="text-muted">Commencez par ajouter des produits à votre stock.</p>
                
                <a href="{{ route('products.create') }}" class="btn btn-modern-primary">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter un produit
                </a>
                
                
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th><i class="fas fa-tag me-1"></i> Prix</th>
                            <th><i class="fas fa-ruler me-1"></i> Taille</th>
                            <th><i class="fas fa-copyright me-1"></i> Marque</th>
                            <th><i class="fas fa-cubes me-1"></i> Quantité</th>
                            <th><i class="fas fa-info-circle me-1"></i> État</th>
                            <th class="text-center"><i class="fas fa-cog me-1"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">{{ number_format($product->price, 2) }} DH</span>
                                </td>
                                <td>
                                    <span class="badge-modern badge-secondary">{{ $product->taille }}</span>
                                </td>
                                <td>
                                    <span class="fw-500">{{ $product->marque ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge-modern badge-dark">
                                        <i class="fas fa-box me-1"></i>
                                        {{ $product->quantite }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusConfig = [
                                            'success' => ['text' => 'En stock', 'icon' => 'fa-check-circle', 'class' => 'badge-success'],
                                            'warning' => ['text' => 'Stock faible', 'icon' => 'fa-exclamation-triangle', 'class' => 'badge-warning'],
                                            'danger' => ['text' => 'Rupture', 'icon' => 'fa-times-circle', 'class' => 'badge-danger'],
                                        ];
                                        $status = $statusConfig[$product->stock_status] ?? ['text' => 'Inconnu', 'icon' => 'fa-question-circle', 'class' => 'badge-secondary'];
                                    @endphp
                                    <span class="badge-modern {{ $status['class'] }}">
                                        <i class="fas {{ $status['icon'] }}"></i>
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="action-btn-group justify-content-center">
                                        
                                        <a href="{{ route('products.edit', $product->id) }}" 
                                           class="action-btn action-btn-edit" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        
                                        <button type="button" 
                                                class="action-btn action-btn-delete" 
                                                onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->marque ?? 'N/A') }}', '{{ $product->taille }}', '{{ number_format($product->price, 2) }}')"
                                                title="Supprimer">
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
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Affichage de {{ $products->firstItem() }} à {{ $products->lastItem() }} sur {{ $products->total() }} produits
                </div>
                <div>
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="stat-icon danger mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
                <p class="text-center mb-3 fs-5 fw-500">Êtes-vous sûr de vouloir supprimer ce produit ?</p>
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="fas fa-box me-2"></i>
                    <div>
                        <strong>Produit:</strong> <span id="productToDelete"></span>
                    </div>
                </div>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Cette action est irréversible et supprimera définitivement le produit.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modern-outline" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <form method="POST" id="deleteForm" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-modern-primary" style="background: var(--danger-color);">
                        <i class="fas fa-trash me-1"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, marque, taille, price) {
    const productInfo = `${marque} (${taille}) - ${price} DH`;
    document.getElementById('productToDelete').textContent = productInfo;
    document.getElementById('deleteForm').action = `/products/${id}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Message de confirmation après suppression
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