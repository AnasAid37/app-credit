@extends('layouts.app')

@section('title', 'Modifier Crédit')

@section('content')
<div class="container-fluid">
    <!-- En-tête principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800">Modifier Crédit #{{ $credit->id }}</h1>
            <p class="text-muted mb-0">Mettre à jour les informations du crédit</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('credits.show', $credit) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i> Voir
            </a>
            <a href="{{ route('credits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire principal -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier les informations
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('credits.update', $credit) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                                       value="{{ old('client_name', $credit->client->name) }}"
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
                                       value="{{ old('client_phone', $credit->client->phone) }}"
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
                                          class="form-control @error('client_address') is-invalid @enderror">{{ old('client_address', $credit->client->address) }}</textarea>
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
                                           value="{{ old('amount', $credit->amount) }}"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           required>
                                    <span class="input-group-text">DH</span>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">
                                    Statut <span class="text-danger">*</span>
                                </label>
                                <select id="status" 
                                        name="status"
                                        class="form-control @error('status') is-invalid @enderror">
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

                        <!-- Information de création -->
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Informations de création:</strong><br>
                                    <small>
                                        Créé par: {{ $credit->creator->name ?? 'Système' }} | 
                                        Date: {{ $credit->created_at->format('d/m/Y à H:i') }}
                                        @if($credit->updated_at != $credit->created_at)
                                            | Dernière modification: {{ $credit->updated_at->format('d/m/Y à H:i') }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('credits.index') }}" 
                               class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="btn btn-warning">
                                <i class="fas fa-save me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar - Ajout de paiement -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Ajouter un paiement
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Résumé actuel -->
                    <div class="mb-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <div class="fw-bold text-primary">{{ number_format($credit->amount, 2) }} DH</div>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <div class="fw-bold text-success">{{ number_format($credit->paid_amount, 2) }} DH</div>
                                    <small class="text-muted">Payé</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <div class="fw-bold text-warning">{{ number_format($credit->remaining_amount, 2) }} DH</div>
                                    <small class="text-muted">Restant</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de paiement -->
                    @if($credit->status === 'active' && $credit->remaining_amount > 0)
                    <form action="{{ route('credits.add-payment', $credit) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_amount" class="form-label">Montant du paiement (DH)</label>
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
                            <small class="text-muted">Maximum: {{ number_format($credit->remaining_amount, 2) }} DH</small>
                        </div>

                        <div class="mb-3">
                            <label for="payment_notes" class="form-label">Notes (optionnel)</label>
                            <textarea id="payment_notes" 
                                      name="payment_notes" 
                                      rows="2"
                                      class="form-control"
                                      placeholder="Notes sur le paiement"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-1"></i> Enregistrer le paiement
                        </button>
                    </form>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun paiement possible - crédit {{ $credit->status === 'paid' ? 'payé' : 'annulé' }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection