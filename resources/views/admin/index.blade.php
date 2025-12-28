@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête de la page -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-1">Gestion des Utilisateurs</h1>
                <p class="mb-0 opacity-90">
                    <i class="fas fa-users-cog me-2"></i>
                    Gérez les comptes utilisateurs et leurs abonnements
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                </a>
                <button type="button" class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-user-plus me-2"></i>Nouvel utilisateur
                </button>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-lg me-3"></i>
            <div>
                <strong>Succès!</strong>
                {{ session('success') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-times-circle fa-lg me-3"></i>
            <div>
                <strong>Erreur!</strong>
                {{ session('error') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Barre de recherche et statistiques -->
    <div class="modern-card mb-4">
        <div class="row">
            <div class="col-lg-8">
                <form method="GET" class="search-form">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher par nom, email ou type..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-modern-primary" type="submit">
                            Rechercher
                        </button>
                        @if(request('search'))
                        <a href="{{ route('admin.index') }}" class="btn btn-modern-outline">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="stat-row d-flex justify-content-around">
                    <div class="stat-item text-center">
                        <div class="stat-number text-primary">{{ $users->total() }}</div>
                        <div class="stat-label">Total</div>
                    </div>
                    <div class="stat-item text-center">
                        <div class="stat-number text-success">{{ $users->where('is_active', true)->count() }}</div>
                        <div class="stat-label">Actifs</div>
                    </div>
                    <div class="stat-item text-center">
                        <div class="stat-number text-warning">{{ $users->where('is_active', false)->count() }}</div>
                        <div class="stat-label">Inactifs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="modern-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>
                            <i class="fas fa-user me-1"></i>Utilisateur
                        </th>
                        <th>
                            <i class="fas fa-envelope me-1"></i>Email
                        </th>
                        <th>
                            <i class="fas fa-circle me-1"></i>Statut
                        </th>
                        <th>
                            <i class="fas fa-crown me-1"></i>Type
                        </th>
                        <th>
                            <i class="fas fa-calendar-alt me-1"></i>Expiration
                        </th>
                        <th class="text-center">
                            <i class="fas fa-cog me-1"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div>
                                    <strong class="d-block">{{ $user->nom }}</strong>
                                    <small class="text-muted">{{ $user->role ?? 'Utilisateur' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;" title="{{ $user->email }}">
                                {{ $user->email }}
                            </div>
                        </td>
                        <td>
                            @if($user->is_active)
                            <span class="badge-modern badge-success">
                                <i class="fas fa-check-circle me-1"></i>Actif
                            </span>
                            @else
                            <span class="badge-modern badge-secondary">
                                <i class="fas fa-times-circle me-1"></i>Inactif
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($user->subscription_type === 'lifetime')
                            <span class="badge-modern badge-primary">
                                <i class="fas fa-infinity me-1"></i>À vie
                            </span>
                            @elseif($user->subscription_type === 'monthly')
                            <span class="badge-modern badge-info">
                                <i class="fas fa-calendar me-1"></i>Mensuel
                            </span>
                            @else
                            <span class="badge-modern badge-secondary">
                                <i class="fas fa-ban me-1"></i>Aucun
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($user->subscription_expires_at)
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $user->subscription_expires_at->format('d/m/Y') }}</span>
                                @php 
                                    $days = now()->diffInDays($user->subscription_expires_at, false);
                                    $expired = $days < 0;
                                    $warning = $days >= 0 && $days <= 7;
                                @endphp
                                @if($expired)
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Expiré depuis {{ abs($days) }} jours
                                </small>
                                @elseif($warning)
                                <small class="text-warning">
                                    <i class="fas fa-clock me-1"></i>{{ $days }} jours restants
                                </small>
                                @else
                                <small class="text-muted">{{ $days }} jours restants</small>
                                @endif
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group justify-content-center">
                                @if(!$user->is_active)
                                <button class="action-btn action-btn-success" 
                                        onclick="showActivateModal({{ $user->id }}, '{{ $user->nom }}')"
                                        title="Activer l'utilisateur">
                                    <i class="fas fa-check"></i>
                                </button>
                                @else
                                <form method="POST" action="{{ route('admin.deactivate', $user) }}" 
                                      class="d-inline" onsubmit="return confirmDeactivation('{{ $user->nom }}')">
                                    @csrf
                                    <button type="submit" class="action-btn action-btn-warning" title="Désactiver">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>

                                @if($user->subscription_type === 'monthly')
                                <button class="action-btn action-btn-info" 
                                        onclick="showExtendModal({{ $user->id }}, '{{ $user->nom }}')"
                                        title="Prolonger l'abonnement">
                                    <i class="fas fa-calendar-plus"></i>
                                </button>
                                @endif
                                @endif

                                <button class="action-btn action-btn-primary" 
                                        onclick="showEditModal({{ $user->id }}, '{{ $user->nom }}', '{{ $user->email }}')"
                                        title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <h5>Aucun utilisateur trouvé</h5>
                                <p class="text-muted">Commencez par ajouter un nouvel utilisateur</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="pagination-wrapper-modern">
            <div class="pagination-info-modern">
                <i class="fas fa-info-circle"></i>
                <span>
                    Affichage de <strong>{{ $users->firstItem() }}</strong>
                    à <strong>{{ $users->lastItem() }}</strong>
                    sur <strong>{{ $users->total() }}</strong> utilisateurs
                </span>
            </div>
            <nav>
                {{ $users->links('vendor.pagination.custom') }}
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Modal d'activation -->
<div class="modal fade" id="activateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-check me-2 text-success"></i>
                    Activer l'utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="activateForm">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="modal-avatar success">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <p class="mb-3 fs-5 fw-500">Activer <span id="activateUserName" class="text-primary"></span></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">
                            <i class="fas fa-crown me-1"></i>Type d'abonnement
                        </label>
                        <div class="subscription-types">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="subscription_type" 
                                       id="monthly" value="monthly" checked>
                                <label class="form-check-label" for="monthly">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="fw-bold">Abonnement Mensuel</span>
                                            <div class="text-muted small">Renouvelable chaque mois</div>
                                        </div>
                                        <span class="badge-modern badge-info">Flexible</span>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="subscription_type" 
                                       id="lifetime" value="lifetime">
                                <label class="form-check-label" for="lifetime">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="fw-bold">Abonnement À Vie</span>
                                            <div class="text-muted small">Accès permanent au système</div>
                                        </div>
                                        <span class="badge-modern badge-primary">Permanent</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="monthsField">
                        <label class="form-label fw-500">
                            <i class="fas fa-calendar me-1"></i>Durée (mois)
                        </label>
                        <input type="number" name="months" class="form-control" value="1" min="1" max="24">
                        <div class="form-text">Durée de l'abonnement en mois (1-24)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-outline" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-modern-success">
                        <i class="fas fa-check me-1"></i> Activer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de prolongation -->
<div class="modal fade" id="extendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus me-2 text-info"></i>
                    Prolonger l'abonnement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="extendForm">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="modal-avatar info">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <p class="mb-3 fs-5 fw-500">Prolonger l'abonnement de <span id="extendUserName" class="text-primary"></span></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">
                            <i class="fas fa-calendar me-1"></i>Durée supplémentaire
                        </label>
                        <div class="duration-buttons mb-3">
                            @foreach([1, 3, 6, 12] as $months)
                            <button type="button" class="btn btn-outline-primary duration-btn" data-months="{{ $months }}">
                                {{ $months }} mois
                            </button>
                            @endforeach
                        </div>
                        <input type="number" name="months" class="form-control" value="1" min="1" max="24" required>
                        <div class="form-text">Ajouter des mois à l'abonnement actuel</div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <div class="small">L'abonnement sera prolongé à partir de la date d'expiration actuelle</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-outline" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-modern-info">
                        <i class="fas fa-calendar-check me-1"></i> Prolonger
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2 text-primary"></i>
                    Modifier l'utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-500">
                            <i class="fas fa-user me-1"></i>Nom complet
                        </label>
                        <input type="text" name="nom" id="editUserName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">
                            <i class="fas fa-envelope me-1"></i>Email
                        </label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">
                            <i class="fas fa-key me-1"></i>Mot de passe (optionnel)
                        </label>
                        <input type="password" name="password" class="form-control">
                        <div class="form-text">Laisser vide pour ne pas modifier</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-outline" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques à la page de gestion des utilisateurs */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.stat-row {
    background: #f8f9fc;
    border-radius: 0.75rem;
    padding: 1rem;
    border: 1px solid #e3e6f0;
}

.stat-item {
    padding: 0 1rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
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
}

.action-btn-success {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.action-btn-success:hover {
    background: #198754;
    color: white;
    transform: translateY(-2px);
}

.action-btn-warning {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.action-btn-warning:hover {
    background: #ffc107;
    color: white;
    transform: translateY(-2px);
}

.action-btn-info {
    background: rgba(13, 202, 240, 0.1);
    color: #0dcaf0;
}

.action-btn-info:hover {
    background: #0dcaf0;
    color: white;
    transform: translateY(-2px);
}

.action-btn-primary {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.action-btn-primary:hover {
    background: #0d6efd;
    color: white;
    transform: translateY(-2px);
}

.modal-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
}

.modal-avatar.success {
    background: linear-gradient(135deg, #198754 0%, #157347 100%);
    color: white;
}

.modal-avatar.info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0ba5d1 100%);
    color: white;
}

.subscription-types {
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    padding: 1rem;
}

.subscription-types .form-check-label {
    width: 100%;
    padding: 0.5rem 0;
}

.duration-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.duration-btn {
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.duration-btn.active {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
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
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.badge-secondary {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.badge-primary {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.badge-info {
    background: rgba(13, 202, 240, 0.1);
    color: #0dcaf0;
}

/* Table styles */
.table-modern {
    border-collapse: separate;
    border-spacing: 0;
}

.table-modern thead th {
    background: #f8fafc;
    color: #1e293b;
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

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

.empty-state h5 {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.empty-state p {
    color: #64748b;
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .stat-row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .action-btn-group {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .table-modern {
        font-size: 0.875rem;
    }
}
</style>

<script>
// Afficher le modal d'activation
function showActivateModal(userId, userName) {
    document.getElementById('activateUserName').textContent = userName;
    document.getElementById('activateForm').action = `/admin/users/${userId}/activate`;
    
    // Reset form
    document.getElementById('monthly').checked = true;
    document.getElementById('monthsField').style.display = 'block';
    document.querySelector('input[name="months"]').value = 1;
    
    const modal = new bootstrap.Modal(document.getElementById('activateModal'));
    modal.show();
}

// Afficher le modal de prolongation
function showExtendModal(userId, userName) {
    document.getElementById('extendUserName').textContent = userName;
    document.getElementById('extendForm').action = `/admin/users/${userId}/extend`;
    
    // Reset duration buttons
    document.querySelectorAll('.duration-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector('input[name="months"]').value = 1;
    
    const modal = new bootstrap.Modal(document.getElementById('extendModal'));
    modal.show();
}

// Afficher le modal d'édition
function showEditModal(userId, userName, userEmail) {
    document.getElementById('editUserName').value = userName;
    document.getElementById('editUserEmail').value = userEmail;
    document.getElementById('editForm').action = `/admin/users/${userId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}

// Confirmation de désactivation
function confirmDeactivation(userName) {
    return confirm(`Êtes-vous sûr de vouloir désactiver ${userName} ?`);
}

// Toggle months field based on subscription type
document.getElementById('monthly')?.addEventListener('change', function() {
    document.getElementById('monthsField').style.display = 'block';
});

document.getElementById('lifetime')?.addEventListener('change', function() {
    document.getElementById('monthsField').style.display = 'none';
});

// Duration buttons functionality
document.querySelectorAll('.duration-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const months = this.getAttribute('data-months');
        document.querySelector('input[name="months"]').value = months;
        
        // Update active state
        document.querySelectorAll('.duration-btn').forEach(b => {
            b.classList.remove('active');
        });
        this.classList.add('active');
    });
});

// Auto-dismiss alerts
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);
</script>
@endsection