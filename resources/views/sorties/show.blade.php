@extends('layouts.app')

@section('title', 'Détails de la sortie')

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

        .info-group {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-group:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.125rem;
            color: var(--dark-color);
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

        .product-card {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            border-radius: 0.5rem;
        }

        .price-highlight {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(52, 211, 153, 0.1) 100%);
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid var(--success-color);
        }

        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 1.5rem 0;
        }

        .credit-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .credit-timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
        }

        .timeline-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 3px solid var(--primary-color);
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.25rem;
            top: 1.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--primary-color);
            transform: translateY(-50%);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .info-value {
                font-size: 1rem;
            }

            .product-card,
            .price-highlight {
                padding: 1rem;
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
                    <h1 class="mb-1">
                        <i class="fas fa-arrow-circle-down me-2"></i>
                        Détails de la sortie #{{ $sortie->id }}
                    </h1>
                    <p class="mb-0 opacity-90">Consultation détaillée d'une sortie de stock</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('sorties.index') }}" class="btn btn-modern-outline">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <button class="btn btn-modern-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche -->
            <div class="col-lg-8">
                <!-- Informations générales -->
                <div class="modern-card">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informations générales
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Numéro de sortie</div>
                                <div class="info-value">
                                    <span class="badge-modern badge-primary">#{{ $sortie->id }}</span>
                                </div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">Date de sortie</div>
                                <div class="info-value">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    {{ $sortie->created_at ? $sortie->created_at->format('d/m/Y') : 'N/A' }}
                                </div>
                                <small class="text-muted d-block mt-1">
                                    {{ $sortie->created_at ? $sortie->created_at->format('H:i') : '' }}
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Mode de paiement</div>
                                <div class="info-value">
                                    @if ($sortie->payment_mode === 'cash')
                                        <span class="badge-modern badge-success">
                                            <i class="fas fa-money-bill-wave"></i>
                                            Paiement comptant
                                        </span>
                                    @else
                                        <span class="badge-modern badge-warning">
                                            <i class="fas fa-hand-holding-usd"></i>
                                            Crédit
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">Utilisateur</div>
                                <div class="info-value">
                                    <i class="fas fa-user-check text-primary me-2"></i>
                                    {{ $sortie->user->nom ?? $sortie->user->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations du client -->
                <div class="modern-card">
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Informations du client
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Nom du client</div>
                                <div class="info-value">
                                    <i class="fas fa-user-circle text-primary me-2"></i>
                                    {{ $sortie->nom_client }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Motif de sortie</div>
                                <div class="info-value">
                                    @php
                                        $motifColors = [
                                            'Entreprise' => 'primary',
                                            'Client particulier' => 'info',
                                            'Retour fournisseur' => 'warning',
                                            'Défectueux' => 'danger',
                                            'Autre' => 'dark',
                                        ];
                                        $color = $motifColors[$sortie->motif_sortie] ?? 'dark';
                                    @endphp
                                    <span class="badge-modern badge-{{ $color }}">
                                        {{ $sortie->motif_sortie }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Détails du produit -->
                <div class="modern-card">
                    <div class="section-title">
                        <i class="fas fa-box"></i>
                        Détails du produit
                    </div>

                    <div class="product-card">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="info-group">
                                    <div class="info-label">Nom du produit</div>
                                    <div class="info-value">
                                        {{ $sortie->product->marque ?? 'Produit non trouvé' }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <div class="info-label">Taille</div>
                                            <div class="info-value">
                                                <span class="badge-modern badge-info">
                                                    {{ $sortie->product->taille ?? 'Non spécifié' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <div class="info-label">Catégorie</div>
                                            <div class="info-value">
                                                <span class="badge-modern badge-dark">
                                                    {{ $sortie->product->category->nom ?? 'Non catégorisé' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="info-label">Prix unitaire</div>
                                    <div class="h3 text-primary mb-0">
                                        {{ number_format($sortie->product->price ?? 0, 2) }} DH
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Récapitulatif financier -->
                <div class="modern-card">
                    <div class="section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Récapitulatif financier
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Quantité</div>
                                <div class="info-value">
                                    <span class="badge-modern badge-dark">
                                        {{ $sortie->quantite }} unité(s)
                                    </span>
                                </div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">Prix unitaire</div>
                                <div class="info-value text-primary">
                                    {{ number_format($sortie->product->price ?? 0, 2) }} DH
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="price-highlight">
                                <div class="info-label mb-2">Prix total</div>
                                <div style="font-size: 1.75rem; font-weight: 700; color: var(--success-color);">
                                    {{ number_format($sortie->total_price, 2) }} DH
                                </div>
                                <small class="text-muted d-block mt-2">
                                    {{ $sortie->quantite }} × {{ number_format($sortie->product->price ?? 0, 2) }} DH
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="col-lg-4">
                <!-- Résumé rapide -->
                <div class="modern-card">
                    <div class="section-title" style="margin-bottom: 1rem;">
                        <i class="fas fa-clipboard-list"></i>
                        Résumé rapide
                    </div>

                    <div class="info-group">
                        <div class="info-label">État de la sortie</div>
                        <div class="info-value">
                            <span class="badge-modern badge-success">
                                <i class="fas fa-check-circle"></i>
                                Enregistrée
                            </span>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Total produits</div>
                        <div class="info-value">{{ $sortie->quantite }} unité(s)</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Montant total</div>
                        <div class="info-value text-success">
                            {{ number_format($sortie->total_price, 2) }} DH
                        </div>
                    </div>

                    @if ($sortie->payment_mode === 'cash')
                        <div class="info-group">
                            <div class="info-label">Statut de paiement</div>
                            <div class="info-value">
                                <span class="badge-modern badge-success">
                                    <i class="fas fa-check"></i> Payé
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Informations de crédit -->
                @if ($sortie->payment_mode === 'credit' && $sortie->credit)
                    <div class="modern-card">
                        <div class="section-title" style="margin-bottom: 1rem;">
                            <i class="fas fa-credit-card"></i>
                            Informations de crédit
                        </div>

                        <div class="info-group">
                            <div class="info-label">Numéro du crédit</div>
                            <div class="info-value">
                                <a href="{{ route('credits.show', $sortie->credit->id) }}" class="text-primary fw-bold">
                                    #{{ $sortie->credit->id }}
                                </a>
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Montant total du crédit</div>
                            <div class="info-value text-warning">
                                {{ number_format($sortie->credit->amount, 2) }} DH
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Montant payé</div>
                            <div class="info-value text-success">
                                {{ number_format($sortie->credit->paid_amount, 2) }} DH
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Montant restant</div>
                            <div class="info-value text-danger">
                                {{ number_format($sortie->credit->remaining_amount, 2) }} DH
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Statut du crédit</div>
                            <div class="info-value">
                                @if ($sortie->credit->status === 'paid')
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-check"></i> Soldé
                                    </span>
                                @elseif ($sortie->credit->status === 'active')
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-hourglass-half"></i> En cours
                                    </span>
                                @else
                                    <span class="badge-modern badge-danger">
                                        <i class="fas fa-times"></i> Annulé
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="divider"></div>

                        <a href="{{ route('credits.show', $sortie->credit->id) }}" class="btn btn-modern-primary w-100">
                            <i class="fas fa-eye"></i> Voir le crédit complet
                        </a>
                    </div>

                    <!-- Paiements liés -->
                    @if ($sortie->credit && $sortie->credit->payments->count() > 0)
                        <div class="modern-card">
                            <div class="section-title" style="margin-bottom: 1rem;">
                                <i class="fas fa-receipt"></i>
                                Paiements effectués
                            </div>

                            <div class="credit-timeline">
                                @foreach ($sortie->credit->payments as $payment)
                                    <div class="timeline-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    {{ number_format($payment->amount, 2) }} DH
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y H:i') : 'N/A' }}
                                                </small>
                                            </div>
                                            <span class="badge-modern badge-success">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                        @if ($payment->notes)
                                            <small class="text-muted d-block mt-2">
                                                <i class="fas fa-sticky-note me-1"></i>
                                                {{ $payment->notes }}
                                            </small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection