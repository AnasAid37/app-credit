@extends('layouts.app')

@section('title', 'Mon Profil - Pneumatique Gestion Complète')

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

    .profile-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 1.5rem;
        border: 4px solid white;
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 24px rgba(0,0,0,0.2);
    }

    .modern-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
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
    .stat-card.info::before { background: var(--info-color); }
    .stat-card.warning::before { background: var(--warning-color); }

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
    .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: var(--info-color); }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }

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

    .nav-tabs-modern {
        border: none;
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
    }

    .nav-tabs-modern .nav-link {
        border: none;
        color: #64748b;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .nav-tabs-modern .nav-link:hover {
        background: #f8fafc;
        color: var(--primary-color);
    }

    .nav-tabs-modern .nav-link.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark-color);
    }

    .security-card {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        border: 2px solid rgba(99, 102, 241, 0.2);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .security-badge {
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

    .activity-timeline {
        position: relative;
        padding-left: 2.5rem;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, var(--primary-color) 0%, transparent 100%);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        background: white;
        padding: 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.3rem;
        top: 1.2rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 3px solid white;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        z-index: 1;
    }

    .password-strength-bar {
        height: 6px;
        border-radius: 3px;
        margin-top: 0.5rem;
        transition: all 0.3s ease;
        background: #e2e8f0;
        overflow: hidden;
    }

    .password-strength-bar::after {
        content: '';
        display: block;
        height: 100%;
        transition: all 0.3s ease;
    }

    .password-weak::after { background: var(--danger-color); width: 25%; }
    .password-medium::after { background: var(--warning-color); width: 50%; }
    .password-strong::after { background: #3b82f6; width: 75%; }
    .password-very-strong::after { background: var(--success-color); width: 100%; }

    .status-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .status-item:hover {
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .status-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
    .status-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary-color); }
    .status-icon.info { background: rgba(59, 130, 246, 0.1); color: var(--info-color); }

    .btn-modern {
        padding: 0.625rem 1.5rem;
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

    .modal-modern .modal-content {
        border: none;
        border-radius: 1rem;
        box-shadow: var(--card-hover-shadow);
    }

    .modal-modern .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem;
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

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
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

    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        margin: 1.5rem 0;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem 1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête du profil -->
    <div class="profile-header">
        <div class="row align-items-center g-4">
            <div class="col-lg-2 text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nom) }}&background=667eea&color=fff&size=120" 
                     alt="Avatar" class="profile-avatar">
            </div>
            <div class="col-lg-7">
                <h1 class="h2 mb-2 fw-bold">{{ $user->nom }}</h1>
                <div class="d-flex flex-wrap gap-3 mb-2">
                    <span class="opacity-90">
                        <i class="fas fa-briefcase me-2"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="opacity-90">
                        <i class="fas fa-envelope me-2"></i>
                        {{ $user->email }}
                    </span>
                    @if($user->telephone)
                    <span class="opacity-90">
                        <i class="fas fa-phone me-2"></i>
                        {{ $user->telephone }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 text-lg-end text-center">
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-edit me-1"></i> Modifier le profil
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary">
                <div class="stat-icon primary">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">{{ $stats['sorties_this_month'] }}</div>
                <div class="stat-label">Sorties ce mois</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card success">
                <div class="stat-icon success">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-value">{{ $stats['products_in_stock'] }}</div>
                <div class="stat-label">Produits en stock</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card info">
                <div class="stat-icon info">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="stat-value">{{ $stats['active_credits'] }}</div>
                <div class="stat-label">Crédits actifs</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-icon warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value">{{ $stats['active_alerts'] }}</div>
                <div class="stat-label">Alertes en cours</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Navigation secondaire -->
        <div class="col-lg-3">
            <!-- Onglets de navigation -->
            <div class="nav-tabs-modern">
                <ul class="nav flex-column mb-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#informations" data-bs-toggle="tab">
                            <i class="fas fa-user-circle"></i>
                            Informations personnelles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#securite" data-bs-toggle="tab">
                            <i class="fas fa-shield-alt"></i>
                            Sécurité
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#activite" data-bs-toggle="tab">
                            <i class="fas fa-chart-line"></i>
                            Activité récente
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#parametres" data-bs-toggle="tab">
                            <i class="fas fa-cog"></i>
                            Paramètres
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Carte de statut -->
            <div class="modern-card">
                <h6 class="mb-3 fw-bold text-dark">Statut du compte</h6>
                <div class="status-item">
                    <div class="status-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold" style="font-size: 0.9375rem;">Compte actif</h6>
                        <small class="text-muted">Accès complet</small>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-icon primary">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold" style="font-size: 0.9375rem;">Email</h6>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-icon info">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold" style="font-size: 0.9375rem;">Membre depuis</h6>
                        <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu des onglets -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- Onglet Informations personnelles -->
                <div class="tab-pane fade show active" id="informations">
                    <div class="modern-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-user-circle text-primary"></i>
                                Informations personnelles
                            </h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nom complet</span>
                                    <span class="info-value">{{ $user->nom }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Téléphone</span>
                                    <span class="info-value">{{ $user->telephone ?? 'Non spécifié' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Rôle</span>
                                    <span>
                                        <span class="security-badge badge-primary">
                                            <i class="fas fa-user-tag"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Date d'embauche</span>
                                    <span class="info-value">{{ $profile->date_embauche ? $profile->date_embauche->format('d M Y') : 'Non spécifié' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Département</span>
                                    <span class="info-value">{{ $profile->departement }}</span>
                                </div>
                            </div>
                        </div>

                        @if($profile->adresse)
                        <div class="info-item">
                            <span class="info-label">Adresse</span>
                            <span class="info-value">{{ $profile->adresse }}</span>
                        </div>
                        @endif
                        
                        <div class="divider"></div>

                        <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit"></i>
                            Modifier mes informations
                        </button>
                    </div>
                </div>

                <!-- Onglet Sécurité -->
                <div class="tab-pane fade" id="securite">
                    <div class="modern-card">
                        <h5 class="mb-4 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-shield-alt text-primary"></i>
                            Sécurité du compte
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="security-card">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-bold">Mot de passe</h6>
                                        <span class="security-badge badge-{{ $passwordStrength['level'] == 'strong' ? 'success' : ($passwordStrength['level'] == 'medium' ? 'warning' : 'danger') }}">
                                            <i class="fas fa-shield-alt"></i>
                                            {{ $passwordStrength['text'] }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        @if($passwordStrength['weeks'] === 'Jamais')
                                            Jamais modifié
                                        @else
                                            Modifié il y a {{ $passwordStrength['weeks'] }} semaine(s)
                                        @endif
                                    </p>
                                    <button class="btn btn-modern-outline btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="fas fa-key"></i>
                                        Changer le mot de passe
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="security-card">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-bold">Authentification 2FA</h6>
                                        <span class="security-badge badge-{{ $profile->two_factor_enabled ? 'success' : 'warning' }}">
                                            <i class="fas fa-{{ $profile->two_factor_enabled ? 'check-circle' : 'exclamation-circle' }}"></i>
                                            {{ $profile->two_factor_enabled ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-3">Ajoutez une couche de sécurité supplémentaire</p>
                                    <button class="btn btn-modern-outline btn-sm">
                                        <i class="fas fa-mobile-alt"></i>
                                        {{ $profile->two_factor_enabled ? 'Désactiver' : 'Activer' }} 2FA
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <h6 class="mb-3 fw-bold">Dernières connexions</h6>
                        <div class="security-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="fas fa-desktop text-primary"></i>
                                        <strong>Session actuelle</strong>
                                    </div>
                                    <small class="text-muted">{{ request()->ip() }} · {{ Str::limit(request()->userAgent(), 50) }}</small>
                                </div>
                                <span class="security-badge badge-success">
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                    Actif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Activité récente -->
                <div class="tab-pane fade" id="activite">
                    <div class="modern-card">
                        <h5 class="mb-4 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-chart-line text-primary"></i>
                            Activité récente
                        </h5>

                        @if($activities->isEmpty())
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5 class="text-muted">Aucune activité récente</h5>
                                <p class="text-muted mb-0">Votre historique d'activité apparaîtra ici</p>
                            </div>
                        @else
                            <div class="activity-timeline">
                                @foreach($activities as $activity)
                                <div class="timeline-item">
                                    <h6 class="mb-1 fw-bold">{{ $activity->action }}</h6>
                                    <p class="mb-1 text-muted small">{{ $activity->description }}</p>
                                    <small class="text-primary">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Onglet Paramètres -->
                <div class="tab-pane fade" id="parametres">
                    <div class="modern-card">
                        <h5 class="mb-4 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-cog text-primary"></i>
                            Paramètres du compte
                        </h5>

                        <form action="{{ route('profile.settings') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Préférences de langue</label>
                                    <select class="form-select" name="langue">
                                        <option value="Français" {{ $profile->langue == 'Français' ? 'selected' : '' }}>Français</option>
                                        <option value="Arabe" {{ $profile->langue == 'Arabe' ? 'selected' : '' }}>Arabe</option>
                                        <option value="Anglais" {{ $profile->langue == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fuseau horaire</label>
                                    <select class="form-select" name="fuseau_horaire">
                                        <option value="Africa/Casablanca" {{ $profile->fuseau_horaire == 'Africa/Casablanca' ? 'selected' : '' }}>Afrique/Casablanca (UTC+1)</option>
                                        <option value="Europe/Paris" {{ $profile->fuseau_horaire == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (UTC+1)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <h6 class="mb-3 fw-bold">Préférences de notification</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="notif_stock_faible" value="1" id="notif1" {{ $profile->notif_stock_faible ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notif1">
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                            Alertes de stock faible
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="notif_commandes" value="1" id="notif2" {{ $profile->notif_commandes ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notif2">
                                            <i class="fas fa-shopping-cart text-primary me-2"></i>
                                            Notifications de commandes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="notif_rapports" value="1" id="notif3" {{ $profile->notif_rapports ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notif3">
                                            <i class="fas fa-chart-bar text-info me-2"></i>
                                            Rapports hebdomadaires
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="notif_promotions" value="1" id="notif4" {{ $profile->notif_promotions ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notif4">
                                            <i class="fas fa-tags text-success me-2"></i>
                                            Promotions et offres
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-modern-primary">
                                    <i class="fas fa-save"></i>
                                    Enregistrer les modifications
                                </button>
                                <button type="reset" class="btn btn-modern-outline">
                                    <i class="fas fa-times"></i>
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier le profil -->
<div class="modal fade modal-modern" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Modifier le profil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" value="{{ old('nom', $user->nom) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" value="{{ old('telephone', $user->telephone) }}" placeholder="+212 6XX XXX XXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Département</label>
                            <input type="text" class="form-control" name="departement" value="{{ old('departement', $profile->departement) }}" placeholder="Ex: Ventes">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date d'embauche</label>
                            <input type="date" class="form-control" name="date_embauche" value="{{ old('date_embauche', $profile->date_embauche?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Adresse</label>
                            <textarea class="form-control" name="adresse" rows="3" placeholder="Adresse complète">{{ old('adresse', $profile->adresse) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-outline" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Changer le mot de passe -->
<div class="modal fade modal-modern" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2"></i>
                    Changer le mot de passe
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.password') }}" method="POST" id="passwordForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mot de passe actuel <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="current_password" placeholder="Entrez votre mot de passe actuel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="newPassword" placeholder="Minimum 8 caractères" required>
                        <div class="password-strength-bar mt-2"></div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Minimum 8 caractères avec majuscules, minuscules et chiffres
                        </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmez le mot de passe" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern-outline" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fas fa-save"></i>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la force du mot de passe
    const newPasswordInput = document.getElementById('newPassword');
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthBar = document.querySelector('.password-strength-bar');
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('password-weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('password-medium');
            } else if (strength <= 4) {
                strengthBar.classList.add('password-strong');
            } else {
                strengthBar.classList.add('password-very-strong');
            }
        });
    }

    // Gestion des onglets Bootstrap
    const triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'));
    triggerTabList.forEach(function (triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});

// Message de succès après action
@if(session('success'))
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
@endpush