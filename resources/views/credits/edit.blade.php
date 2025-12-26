@extends('layouts.app')

@section('title', 'Modifier Crédit')

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
    }

    body {
        background-color: var(--light-bg);
    }

    .page-header {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
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
        box-shadow: var(--card-shadow);
        border: none;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-header-modern {
        padding: 1.5rem;
        border: none;
    }

    .card-header-modern.warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        color: white;
    }

    .card-header-modern.success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
    }

    .card-header-modern h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .form-label {
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.625rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9375rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .input-group-text {
        background: var(--light-bg);
        border: 2px solid #e2e8f0;
        border-left: none;
        color: #64748b;
        font-weight: 500;
    }

    .input-group .form-control {
        border-right: none;
    }

    .summary-box {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .summary-box:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .summary-value {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
    }

    .alert-info-modern {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
        border: 2px solid rgba(59, 130, 246, 0.2);
        border-radius: 0.75rem;
        padding: 1rem;
        color: var(--dark-color);
    }

    .btn-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
    }

    .btn-modern-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        color: white;
    }

    .btn-modern-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.4);
        color: white;
    }

    .btn-modern-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
    }

    .btn-modern-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-modern-info {
        background: linear-gradient(135deg, var(--info-color) 0%, var(--primary-color) 100%);
        color: white;
    }

    .btn-modern-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-modern-secondary {
        background: white;
        border: 2px solid #e2e8f0;
        color: #64748b;
    }

    .btn-modern-secondary:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    @media (max-width: 992px) {
        .page-header h1 {
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
                <h1 class="mb-1">Modifier Crédit #{{ $credit->id }}</h1>
                <p class="mb-0 opacity-90">
                    <i class="fas fa-edit me-2"></i>
                    Mettre à jour les informations du crédit
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('credits.show', $credit) }}" class="btn btn-light">
                    <i class="fas fa-eye"></i>
                    Voir
                </a>
                <a href="{{ route('credits.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire principal -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="card-header-modern warning">
                    <h5>
                        <i class="fas fa-edit"></i>
                        Modifier les informations
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('credits.update', $credit) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Section Client -->
                        <div class="mb-5">
                            <h6 class="section-title">
                                <i class="fas fa-user"></i>
                                Informations du client
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="client_name" class="form-label">
                                        Nom du client <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="client_name" 
                                           name="client_name" 
                                           value="{{ old('client_name', $credit->client->name ?? $credit->client_name) }}"
                                           class="form-control @error('client_name') is-invalid @enderror"
                                           required>
                                    @error('client_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="client_phone" class="form-label">Téléphone</label>
                                    <input type="tel" 
                                           id="client_phone" 
                                           name="client_phone" 
                                           value="{{ old('client_phone', $credit->client->phone ?? $credit->client_phone) }}"
                                           class="form-control @error('client_phone') is-invalid @enderror">
                                    @error('client_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="client_address" class="form-label">Adresse</label>
                                    <textarea id="client_address" 
                                              name="client_address" 
                                              rows="3"
                                              class="form-control @error('client_address') is-invalid @enderror">{{ old('client_address', $credit->client->address ?? $credit->client_address) }}</textarea>
                                    @error('client_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section Crédit -->
                        <div class="mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-money-bill-wave"></i>
                                Détails du crédit
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">
                                        Montant total (DH) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               id="amount" 
                                               name="amount" 
                                               step="0.01" 
                                               min="0.01" 
                                               max="999999.99"
                                               value="{{ old('amount', $credit->amount) }}"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               required>
                                        <span class="input-group-text">DH</span>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">
                                        Statut <span class="text-danger">*</span>
                                    </label>
                                    <select id="status" 
                                            name="status"
                                            class="form-select @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', $credit->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="paid" {{ old('status', $credit->status) === 'paid' ? 'selected' : '' }}>Payé</option>
                                        <option value="cancelled" {{ old('status', $credit->status) === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="reason" class="form-label">Raison du crédit</label>
                                    <textarea id="reason" 
                                              name="reason" 
                                              rows="4"
                                              class="form-control @error('reason') is-invalid @enderror">{{ old('reason', $credit->reason) }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Information de création -->
                        <div class="alert-info-modern">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fas fa-info-circle mt-1" style="color: var(--info-color);"></i>
                                <div>
                                    <strong>Informations de création</strong><br>
                                    <small class="text-muted">
                                        Créé par: {{ $credit->creator->name ?? 'Système' }} | 
                                        Date: {{ $credit->created_at->format('d/m/Y à H:i') }}
                                        @if($credit->updated_at != $credit->created_at)
                                            <br>Dernière modification: {{ $credit->updated_at->format('d/m/Y à H:i') }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-2 pt-4 border-top mt-4">
                            <a href="{{ route('credits.index') }}" 
                               class="btn btn-modern-secondary">
                                <i class="fas fa-times"></i>
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="btn btn-modern-warning">
                                <i class="fas fa-save"></i>
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar - Ajout de paiement -->
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="card-header-modern success">
                    <h5>
                        <i class="fas fa-plus-circle"></i>
                        Ajouter un paiement
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Résumé actuel -->
                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="summary-box" style="border-color: rgba(99, 102, 241, 0.3);">
                                    <div class="summary-value text-primary">
                                        {{ number_format($credit->amount, 2) }}
                                    </div>
                                    <div class="summary-label">Total DH</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="summary-box" style="border-color: rgba(16, 185, 129, 0.3);">
                                    <div class="summary-value text-success">
                                        {{ number_format($credit->paid_amount, 2) }}
                                    </div>
                                    <div class="summary-label">Payé DH</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="summary-box" style="border-color: rgba(245, 158, 11, 0.3);">
                                    <div class="summary-value" style="color: var(--warning-color);">
                                        {{ number_format($credit->remaining_amount, 2) }}
                                    </div>
                                    <div class="summary-label">Restant DH</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de paiement -->
                    @if($credit->status === 'active' && $credit->remaining_amount > 0)
                    <form action="{{ route('credits.add-payment', $credit) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_amount" class="form-label">
                                Montant du paiement (DH) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       id="payment_amount" 
                                       name="payment_amount" 
                                       step="0.01" 
                                       min="0.01" 
                                       max="{{ $credit->remaining_amount }}"
                                       class="form-control"
                                       placeholder="0.00"
                                       required>
                                <span class="input-group-text">DH</span>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Maximum: {{ number_format($credit->remaining_amount, 2) }} DH
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="payment_notes" class="form-label">Notes (optionnel)</label>
                            <textarea id="payment_notes" 
                                      name="payment_notes" 
                                      rows="3"
                                      class="form-control"
                                      placeholder="Notes sur le paiement"></textarea>
                        </div>

                        <button type="submit" class="btn btn-modern-success w-100">
                            <i class="fas fa-check"></i>
                            Enregistrer le paiement
                        </button>
                    </form>
                    @else
                    <div class="alert-info-modern text-center">
                        <i class="fas fa-info-circle me-2" style="color: var(--info-color);"></i>
                        <div class="mt-2">
                            Aucun paiement possible<br>
                            <small>Crédit {{ $credit->status === 'paid' ? 'payé' : 'annulé' }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection