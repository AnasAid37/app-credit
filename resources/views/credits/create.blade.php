@extends('layouts.app')

@section('title', 'Nouveau Crédit')

@push('styles')
<style>
    :root {
        --primary-color: #6366f1;
        --secondary-color: #8b5cf6;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --dark-color: #1e293b;
        --light-bg: #f8fafc;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    body {
        background-color: var(--light-bg);
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
        box-shadow: var(--card-shadow);
        border: none;
        overflow: hidden;
    }

    .card-header-modern {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
        padding: 1.5rem;
        border: none;
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

    .form-label .text-danger {
        font-weight: 700;
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

    .form-control.is-invalid {
        border-color: var(--danger-color);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
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

    .alert-info-modern {
        background: rgba(99, 102, 241, 0.1);
        border: 2px solid rgba(99, 102, 241, 0.2);
        border-radius: 0.75rem;
        padding: 1rem;
        color: var(--dark-color);
    }

    .alert-warning-modern {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.1) 100%);
        border: 2px solid rgba(245, 158, 11, 0.2);
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .remaining-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--warning-color);
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
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
    }

    .btn-modern-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
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

    .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: var(--danger-color);
        font-weight: 500;
    }

    @media (max-width: 768px) {
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
                <h1 class="mb-1">Nouveau Crédit</h1>
                <p class="mb-0 opacity-90">
                    <i class="fas fa-plus-circle me-2"></i>
                    Créer un nouveau crédit client
                </p>
            </div>
            <a href="{{ route('credits.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="modern-card">
                <div class="card-header-modern">
                    <h5>
                        <i class="fas fa-file-invoice-dollar"></i>
                        Informations du crédit
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('credits.store') }}" method="POST">
                        @csrf

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
                                           value="{{ old('client_name') }}"
                                           class="form-control @error('client_name') is-invalid @enderror"
                                           placeholder="Nom complet du client"
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
                                           value="{{ old('client_phone') }}"
                                           class="form-control @error('client_phone') is-invalid @enderror"
                                           placeholder="0612345678">
                                    @error('client_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="client_address" class="form-label">Adresse</label>
                                    <textarea id="client_address" 
                                              name="client_address" 
                                              rows="3"
                                              class="form-control @error('client_address') is-invalid @enderror"
                                              placeholder="Adresse du client (optionnel)">{{ old('client_address') }}</textarea>
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
                                               value="{{ old('amount') }}"
                                               onchange="calculateRemaining()"
                                               oninput="calculateRemaining()"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               placeholder="0.00"
                                               required>
                                        <span class="input-group-text">DH</span>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="paid_amount" class="form-label">Montant payé initialement (DH)</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               id="paid_amount" 
                                               name="paid_amount" 
                                               step="0.01" 
                                               min="0" 
                                               value="{{ old('paid_amount', 0) }}"
                                               onchange="calculateRemaining()"
                                               oninput="calculateRemaining()"
                                               class="form-control @error('paid_amount') is-invalid @enderror"
                                               placeholder="0.00">
                                        <span class="input-group-text">DH</span>
                                    </div>
                                    @error('paid_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Affichage du montant restant -->
                                <div class="col-12 mb-3">
                                    <div class="alert-warning-modern">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3">
                                                <div style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.2); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-wallet" style="font-size: 1.5rem; color: var(--warning-color);"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small fw-500 mb-1">Montant restant à payer</div>
                                                    <div class="remaining-amount" id="remaining_display">0.00 DH</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="reason" class="form-label">Raison du crédit</label>
                                    <textarea id="reason" 
                                              name="reason" 
                                              rows="4"
                                              class="form-control @error('reason') is-invalid @enderror"
                                              placeholder="Décrivez la raison du crédit (optionnel)">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-end gap-2 pt-4 border-top">
                            <a href="{{ route('credits.index') }}" 
                               class="btn btn-modern-secondary">
                                <i class="fas fa-times"></i>
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="btn btn-modern-success">
                                <i class="fas fa-save"></i>
                                Créer le crédit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateRemaining() {
    const totalAmount = parseFloat(document.getElementById('amount').value) || 0;
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    const remaining = Math.max(0, totalAmount - paidAmount);
    
    document.getElementById('remaining_display').textContent = 
        new Intl.NumberFormat('fr-FR', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        }).format(remaining) + ' DH';
}

// Calculer au chargement de la page
document.addEventListener('DOMContentLoaded', calculateRemaining);
</script>
@endsection