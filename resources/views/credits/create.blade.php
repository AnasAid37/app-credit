@extends('layouts.app')

@section('title', 'Nouveau Crédit')

@section('content')
<div class="container-fluid">
    <!-- En-tête principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800">Nouveau Crédit</h1>
            <p class="text-muted mb-0">Créer un nouveau crédit client</p>
        </div>
        <div>
            <a href="{{ route('credits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Informations du crédit
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('credits.store') }}" method="POST">
                        @csrf

                        <!-- Section Client -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Informations du client
                                </h6>
                            </div>
                            
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

                        <!-- Section Crédit -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-money-bill-wave me-2"></i>Détails du crédit
                                </h6>
                            </div>
                            
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
                                           class="form-control @error('amount') is-invalid @enderror"
                                           placeholder="0.00"
                                           required>
                                    <span class="input-group-text">DH</span>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                           class="form-control @error('paid_amount') is-invalid @enderror"
                                           placeholder="0.00">
                                    <span class="input-group-text">DH</span>
                                    @error('paid_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Affichage du montant restant -->
                            <div class="col-12 mb-3">
                                <div class="alert alert-warning">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div>
                                            <strong>Montant restant:</strong>
                                            <span id="remaining_display" class="ms-2 fw-bold">0.00 DH</span>
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

                        <!-- Boutons d'action -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('credits.index') }}" 
                                       class="btn btn-secondary">
                                        Annuler
                                    </a>
                                    <button type="submit" 
                                            class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Créer le crédit
                                    </button>
                                </div>
                            </div>
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
    const remaining = totalAmount - paidAmount;
    
    document.getElementById('remaining_display').textContent = 
        new Intl.NumberFormat('fr-FR', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        }).format(Math.max(0, remaining)) + ' DH';
}

// Calculer au chargement de la page
document.addEventListener('DOMContentLoaded', calculateRemaining);
</script>
@endsection