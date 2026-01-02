@extends('layouts.app')

@section('title', 'Détails du Crédit')

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
            background: linear-gradient(135deg, var(--info-color) 0%, var(--primary-color) 100%);
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

        .stat-card.primary::before {
            background: var(--primary-color);
        }

        .stat-card.success::before {
            background: var(--success-color);
        }

        .stat-card.warning::before {
            background: var(--warning-color);
        }

        .stat-card.info::before {
            background: var(--info-color);
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

        .stat-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }

        .stat-value {
            font-size: 1.75rem;
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

        .progress-modern {
            height: 24px;
            border-radius: 0.75rem;
            background: #f1f5f9;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        .progress-bar-modern {
            background: linear-gradient(90deg, var(--success-color) 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            transition: width 0.6s ease;
        }

        .info-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .info-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        .client-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 2.5rem;
            margin: 0 auto 1rem;
            box-shadow: var(--card-shadow);
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

        .btn-modern-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-modern-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4);
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
        }

        .table-modern tbody tr {
            transition: all 0.2s ease;
            background: white;
        }

        .table-modern tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 1.5rem 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .danger-zone {
            border: 2px solid rgba(239, 68, 68, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            background: rgba(239, 68, 68, 0.02);
        }

        .modal-modern .modal-content {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--card-hover-shadow);
        }

        .modal-modern .modal-header {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            border-radius: 1rem 1rem 0 0;
            padding: 1.5rem;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .stat-value {
                font-size: 1.5rem;
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
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Crédit #{{ $credit->id }}
                    </h1>
                    <p class="mb-0 opacity-90">Détails complets du crédit</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('credits.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                    <a href="{{ route('credits.edit', $credit) }}" class="btn btn-light">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card info" style="padding: 1rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon info" style="width: 50px; height: 50px; font-size: 1.25rem; margin: 0;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <div class="stat-value" style="font-size: 1.5rem;">{{ number_format($credit->amount, 0) }} DH
                            </div>
                            <div class="stat-label" style="font-size: 0.7rem;">Montant Total</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card success" style="padding: 1rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon success" style="width: 50px; height: 50px; font-size: 1.25rem; margin: 0;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="stat-value" style="font-size: 1.5rem;">{{ number_format($credit->paid_amount, 0) }}
                                DH</div>
                            <div class="stat-label" style="font-size: 0.7rem;">Montant Payé</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card warning" style="padding: 1rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon warning" style="width: 50px; height: 50px; font-size: 1.25rem; margin: 0;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div class="stat-value" style="font-size: 1.5rem;">
                                {{ number_format($credit->remaining_amount, 0) }} DH</div>
                            <div class="stat-label" style="font-size: 0.7rem;">Restant</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card primary" style="padding: 1rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon primary" style="width: 50px; height: 50px; font-size: 1.25rem; margin: 0;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <div class="stat-value" style="font-size: 1.5rem;">
                                {{ number_format(($credit->paid_amount / $credit->amount) * 100, 0) }}%</div>
                            <div class="stat-label" style="font-size: 0.7rem;">Progression</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques additionnelles -->
        <div class="row g-4 mb-4">
            <!-- Top Produits liés -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <h6 class="mb-3 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-chart-bar text-primary"></i>
                        Produits liés à ce crédit
                    </h6>
                    @if ($topProducts && $topProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-end">Valeur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProducts as $item)
                                        <tr>
                                            <td>
                                                <i class="fas fa-box text-primary me-1"></i>
                                                {{ $item->product->name ?? 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-modern badge-primary">{{ $item->total_quantity }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                {{ number_format($item->total_value, 2) }} DH
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0 small">Aucun produit lié</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Paiements mensuels -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <h6 class="mb-3 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-calendar-alt text-success"></i>
                        Paiements par mois
                    </h6>
                    @if ($monthlyStats && $monthlyStats->count() > 0)
                        <div class="d-flex flex-column gap-2">
                            @foreach ($monthlyStats as $stat)
                                <div class="d-flex justify-content-between align-items-center p-2 rounded"
                                    style="background: #f8fafc;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar text-primary"></i>
                                        <span class="fw-bold">{{ $stat['month'] }}</span>
                                    </div>
                                    <span class="badge-modern badge-success">
                                        {{ number_format($stat['total'], 2) }} DH
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p class="mb-0 small">Aucun paiement mensuel</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Colonne gauche: Informations du crédit -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <h6 class="mb-4 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle text-primary"></i>
                        Informations du crédit
                    </h6>

                    <!-- Barre de progression -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="info-label">
                                <i class="fas fa-tasks"></i>
                                Progression du paiement
                            </span>
                            <span class="fw-bold text-primary">
                                {{ number_format(($credit->paid_amount / $credit->amount) * 100, 2) }}%
                            </span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern"
                                style="width: {{ ($credit->paid_amount / $credit->amount) * 100 }}%">
                                {{ number_format(($credit->paid_amount / $credit->amount) * 100, 1) }}%
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Détails du montant -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="info-label">
                                <i class="fas fa-money-bill-wave"></i>
                                Montant Total
                            </div>
                            <div class="info-value text-primary">
                                {{ number_format($credit->amount, 2) }} DH
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">
                                <i class="fas fa-check-circle"></i>
                                Montant Payé
                            </div>
                            <div class="info-value text-success">
                                {{ number_format($credit->paid_amount, 2) }} DH
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">
                                <i class="fas fa-wallet"></i>
                                Montant Restant
                            </div>
                            <div class="info-value {{ $credit->remaining_amount > 0 ? 'text-warning' : 'text-success' }}">
                                {{ number_format($credit->remaining_amount, 2) }} DH
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">
                                <i class="fas fa-flag"></i>
                                Statut
                            </div>
                            <div>
                                @php
                                    $remaining = $credit->remaining_amount;
                                    if ($remaining <= 0) {
                                        $status = ['text' => 'Payé', 'class' => 'success', 'icon' => 'check-circle'];
                                    } elseif ($credit->paid_amount > 0) {
                                        $status = ['text' => 'Partiel', 'class' => 'warning', 'icon' => 'clock'];
                                    } else {
                                        $status = [
                                            'text' => 'En attente',
                                            'class' => 'danger',
                                            'icon' => 'exclamation-circle',
                                        ];
                                    }
                                @endphp
                                <span class="badge-modern badge-{{ $status['class'] }}">
                                    <i class="fas fa-{{ $status['icon'] }}"></i>
                                    {{ $status['text'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Raison -->
                    @if ($credit->reason)
                        <div class="divider"></div>
                        <div class="mb-4">
                            <div class="info-label">
                                <i class="fas fa-comment-dots"></i>
                                Raison du crédit
                            </div>
                            <p class="mb-0 text-dark">{{ $credit->reason }}</p>
                        </div>
                    @endif

                    <!-- Dates -->
                    <div class="divider"></div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="info-label">
                                <i class="fas fa-calendar-plus"></i>
                                Date de création
                            </div>
                            <div class="info-value" style="font-size: 1rem;">
                                {{ $credit->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        @if ($credit->updated_at != $credit->created_at)
                            <div class="col-md-6">
                                <div class="info-label">
                                    <i class="fas fa-calendar-check"></i>
                                    Dernière modification
                                </div>
                                <div class="info-value" style="font-size: 1rem;">
                                    {{ $credit->updated_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Créateur -->
                    <div class="divider"></div>
                    <div>
                        <div class="info-label">
                            <i class="fas fa-user-tie"></i>
                            Créé par
                        </div>
                        <div class="info-value" style="font-size: 1rem;">
                            {{ $credit->creator->name ?? 'Système' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite: Informations client -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <h6 class="mb-4 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-user text-primary"></i>
                        Informations du client
                    </h6>

                    <!-- Avatar et nom -->
                    <div class="text-center mb-4">
                        <div class="client-avatar-large">
                            {{ strtoupper(substr($credit->client_name, 0, 2)) }}
                        </div>
                        <h4 class="mb-1 fw-bold">{{ $credit->client_name }}</h4>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-user-circle me-1"></i>
                            Client depuis {{ $credit->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                    <div class="divider"></div>

                    <!-- Coordonnées -->
                    @if ($credit->client_phone)
                        <div class="mb-3">
                            <div class="info-label">
                                <i class="fas fa-phone"></i>
                                Téléphone
                            </div>
                            <a href="tel:{{ $credit->client_phone }}"
                                class="info-value text-decoration-none text-success">
                                {{ $credit->client_phone }}
                            </a>
                        </div>
                    @endif

                    @if ($credit->client_address)
                        <div class="mb-3">
                            <div class="info-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Adresse
                            </div>
                            <p class="mb-0 text-dark">{{ $credit->client_address }}</p>
                        </div>
                    @endif

                    <!-- Autres crédits du client -->
                    @if (isset($otherCredits) && $otherCredits->count() > 0)
                        <div class="divider"></div>
                        <div>
                            <div class="info-label mb-3">
                                <i class="fas fa-history"></i>
                                Autres crédits de ce client
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($otherCredits as $otherCredit)
                                    <div class="d-flex justify-content-between align-items-center p-3 rounded"
                                        style="background: #f8fafc;">
                                        <div>
                                            <small
                                                class="text-muted d-block">{{ $otherCredit->created_at->format('d/m/Y') }}</small>
                                            <span class="fw-bold text-dark">{{ number_format($otherCredit->amount, 2) }}
                                                DH</span>
                                        </div>
                                        <span
                                            class="badge-modern badge-{{ $otherCredit->remaining_amount > 0 ? 'warning' : 'success' }}">
                                            {{ $otherCredit->remaining_amount > 0 ? 'En cours' : 'Payé' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historique des paiements -->
        <div class="modern-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h6 class="m-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-history text-primary"></i>
                    Historique des paiements
                </h6>
                @if ($credit->remaining_amount > 0)
                    <button type="button" class="btn btn-modern-success" data-bs-toggle="modal"
                        data-bs-target="#paymentModal">
                        <i class="fas fa-plus-circle"></i>
                        Ajouter un paiement
                    </button>
                @endif
            </div>

            @if (isset($credit->payments) && $credit->payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar me-1"></i> Date</th>
                                <th><i class="fas fa-money-bill-wave me-1"></i> Montant</th>
                                <th><i class="fas fa-credit-card me-1"></i> Méthode</th>
                                <th><i class="fas fa-hashtag me-1"></i> Référence</th>
                                <th><i class="fas fa-user-check me-1"></i> Enregistré par</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($credit->payments as $payment)
                                <tr>
                                    <td>
                                        <span
                                            class="badge-modern badge-primary">{{ $payment->created_at->format('d/m/Y') }}</span>
                                        <small
                                            class="d-block text-muted mt-1">{{ $payment->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="fw-bold text-success">
                                        {{ number_format($payment->amount, 2) }} DH
                                    </td>
                                    <td>
                                        <span class="badge-modern badge-success">
                                            {{ $payment->payment_method ?? 'Non spécifié' }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->reference ?? 'N/A' }}</td>
                                    <td>{{ $payment->creator->name ?? 'Système' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #f8fafc;">
                                <td colspan="4" class="text-end fw-bold">Total payé:</td>
                                <td class="fw-bold text-success">{{ number_format($credit->paid_amount, 2) }} DH</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5 class="text-muted mb-2">Aucun paiement enregistré</h5>
                    <p class="text-muted mb-3">Ce crédit n'a pas encore de paiements.</p>
                    @if ($credit->remaining_amount > 0)
                        <button type="button" class="btn btn-modern-success" data-bs-toggle="modal"
                            data-bs-target="#paymentModal">
                            <i class="fas fa-plus-circle"></i>
                            Ajouter le premier paiement
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Zone de danger -->
        <div class="modern-card mt-4">
            <h6 class="mb-3 fw-bold text-danger d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i>
                Zone de danger
            </h6>
            <div class="danger-zone">
                <p class="text-muted mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    La suppression de ce crédit est irréversible. Toutes les données associées seront perdues.
                </p>
                <form action="{{ route('credits.destroy', $credit) }}" method="POST"
                    onsubmit="return confirm('⚠️ ATTENTION!\n\nÊtes-vous absolument sûr de vouloir supprimer ce crédit?\n\nCette action est IRRÉVERSIBLE et supprimera:\n- Toutes les informations du crédit\n- L\'historique des paiements\n- Les données associées\n\nTapez OK pour confirmer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-modern-danger">
                        <i class="fas fa-trash"></i>
                        Supprimer définitivement ce crédit
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter un paiement -->
    <div class="modal fade modal-modern" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        Ajouter un paiement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('credits/' . $credit->id . '/add-payment') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="payment_amount" class="form-label fw-bold">
                                Montant à payer <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                                <input type="number" class="form-control" id="payment_amount" name="payment_amount"
                                    step="0.01" min="0.01" max="{{ $credit->remaining_amount }}"
                                    placeholder="0.00" required>
                                <span class="input-group-text">DH</span>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Montant maximum: <strong>{{ number_format($credit->remaining_amount, 2) }} DH</strong>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label fw-bold">Méthode de paiement</label>
                            <select name="payment_method" id="payment_method" class="form-select">
                                <option value="cash">💵 Espèces</option>
                                <option value="check">📝 Chèque</option>
                                <option value="transfer">🏦 Virement</option>
                                <option value="card">💳 Carte bancaire</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="reference" class="form-label fw-bold">Référence (optionnel)</label>
                            <input type="text" class="form-control" id="reference" name="reference"
                                placeholder="Ex: Chèque N°123456">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modern-outline" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-modern-success">
                            <i class="fas fa-save"></i>
                            Enregistrer le paiement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation de la barre de progression au chargement
            const progressBar = document.querySelector('.progress-bar-modern');
            if (progressBar) {
                const width = progressBar.style.width;
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.width = width;
                }, 100);
            }

            // Validation du formulaire de paiement
            const paymentForm = document.querySelector('#paymentModal form');
            const paymentAmountInput = document.getElementById('payment_amount');
            const maxAmount = {{ $credit->remaining_amount }};

            if (paymentForm && paymentAmountInput) {
                paymentAmountInput.addEventListener('input', function() {
                    const value = parseFloat(this.value);
                    if (value > maxAmount) {
                        this.value = maxAmount.toFixed(2);
                    }
                    if (value < 0) {
                        this.value = 0;
                    }
                });

                paymentForm.addEventListener('submit', function(e) {
                    const amount = parseFloat(paymentAmountInput.value);
                    if (amount <= 0 || amount > maxAmount) {
                        e.preventDefault();
                        alert('Le montant doit être entre 0.01 DH et ' + maxAmount.toFixed(2) + ' DH');
                    }
                });
            }
        });

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
