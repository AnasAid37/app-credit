@extends('layouts.app')

@section('title', 'Sortie de stock')

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
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--primary-color);
        font-size: 1.125rem;
    }

    .form-label {
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-label .text-danger {
        color: var(--danger-color) !important;
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

    .form-text {
        color: #64748b;
        font-size: 0.8125rem;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-text.text-info {
        color: var(--info-color) !important;
        font-weight: 500;
    }

    .invalid-feedback {
        color: var(--danger-color);
        font-size: 0.8125rem;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .invalid-feedback::before {
        content: "\f06a";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
    }

    .price-box {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%);
        border: 2px solid rgba(16, 185, 129, 0.2);
        border-radius: 1rem;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .price-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
    }

    .price-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.75rem;
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .price-content {
        flex: 1;
    }

    .price-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .price-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--success-color);
    }

    .alert-modern {
        border-radius: 1rem;
        padding: 1.5rem;
        border: 2px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-modern.warning {
        background: rgba(245, 158, 11, 0.05);
        border-color: rgba(245, 158, 11, 0.2);
        color: #92400e;
    }

    .alert-modern i {
        font-size: 2rem;
        color: var(--warning-color);
    }

    .btn-modern {
        padding: 0.625rem 1.5rem;
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

    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        margin: 1.5rem 0;
    }

    /* Style personnalisé pour Select2 */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 42px !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        transition: all 0.3s ease;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 1rem !important;
        font-size: 0.9375rem;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border: 2px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        box-shadow: var(--card-shadow);
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: var(--primary-color) !important;
        color: white;
    }

    .select2-results__option {
        padding: 0.75rem 1rem !important;
        font-size: 0.9375rem;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        border: 2px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        padding: 0.625rem 1rem !important;
        font-size: 0.9375rem;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--primary-color) !important;
        outline: 0;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
    }

    /* Animation pour le prix total */
    @keyframes priceUpdate {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .price-value.updating {
        animation: priceUpdate 0.3s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.5rem;
        }

        .modern-card {
            padding: 1.5rem;
        }

        .price-value {
            font-size: 1.25rem;
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
                    Sortie de stock
                </h1>
                <p class="mb-0 opacity-90">Enregistrer une nouvelle sortie de produit</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="modern-card">
                @if(empty($products))
                    <div class="alert-modern warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <h6 class="mb-1 fw-bold">Aucun produit disponible</h6>
                            <p class="mb-2">Aucun produit n'est disponible en stock pour le moment.</p>
                            <a href="{{ route('products.create') }}" class="btn btn-modern-primary btn-sm">
                                <i class="fas fa-plus-circle"></i>
                                Ajouter des produits
                            </a>
                        </div>
                    </div>
                @else
                <form method="POST" action="{{ route('sorties.store') }}" id="sortieForm">
                    @csrf

                    <!-- Section Produit -->
                    <div class="section-title">
                        <i class="fas fa-box"></i>
                        Sélection du produit
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="produit_id" class="form-label">
                                Produit <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('produit_id') is-invalid @enderror" 
                                    id="produit_id" 
                                    name="produit_id" 
                                    required>
                                <option value="">-- Sélectionner un produit --</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-quantite="{{ $product->quantite }}" 
                                        data-price="{{ $product->price }}"
                                        {{ old('produit_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->marque ?? 'N/A' }} ({{ $product->taille }}) - Prix: {{ number_format($product->price, 2) }} DH - Stock: {{ $product->quantite }}
                                </option>
                                @endforeach
                            </select>
                            @error('produit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="quantite" class="form-label">
                                Quantité <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8fafc; border: 2px solid #e2e8f0; border-right: none;">
                                    <i class="fas fa-cubes"></i>
                                </span>
                                <input type="number" 
                                       class="form-control @error('quantite') is-invalid @enderror" 
                                       id="quantite" 
                                       name="quantite" 
                                       min="1" 
                                       value="{{ old('quantite', 1) }}" 
                                       style="border-left: none;"
                                       required>
                                @error('quantite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-info" id="stock-available"></div>
                        </div>

                        <!-- Affichage du prix total -->
                        <div class="col-md-6">
                            <label class="form-label">Prix total</label>
                            <div class="price-box">
                                <div class="price-icon">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <div class="price-content">
                                    <div class="price-label">Montant</div>
                                    <div class="price-value" id="total-price">0.00 DH</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Section Client -->
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Informations du client
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="nom_client" class="form-label">
                                Nom du client <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8fafc; border: 2px solid #e2e8f0; border-right: none;">
                                    <i class="fas fa-user-circle"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('nom_client') is-invalid @enderror" 
                                       id="nom_client" 
                                       name="nom_client" 
                                       value="{{ old('nom_client') }}" 
                                       placeholder="Nom complet du client"
                                       style="border-left: none;"
                                       required>
                                @error('nom_client')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="motif_sortie" class="form-label">
                                Motif de sortie <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('motif_sortie') is-invalid @enderror" 
                                    id="motif_sortie" 
                                    name="motif_sortie" 
                                    required>
                                <option value="">-- Sélectionner un motif --</option>
                                <option value="Entreprise" {{ old('motif_sortie') == 'Entreprise' ? 'selected' : '' }}>Entreprise</option>
                                <option value="Client particulier" {{ old('motif_sortie') == 'Client particulier' ? 'selected' : '' }}>Client particulier</option>
                                <option value="Retour fournisseur" {{ old('motif_sortie') == 'Retour fournisseur' ? 'selected' : '' }}>Retour fournisseur</option>
                                <option value="Défectueux" {{ old('motif_sortie') == 'Défectueux' ? 'selected' : '' }}>Produit défectueux</option>
                                <option value="Autre" {{ old('motif_sortie') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('motif_sortie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div id="autre_motif_container" class="d-none">
                                <label for="autre_motif" class="form-label">Précisez le motif</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="autre_motif" 
                                       name="autre_motif" 
                                       placeholder="Précisez le motif" 
                                       value="{{ old('autre_motif') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('dashboard') }}" class="btn btn-modern-outline">
                            <i class="fas fa-times"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-modern-primary">
                            <i class="fas fa-save"></i>
                            Enregistrer la sortie
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Select2 avec recherche
    $('#produit_id').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Sélectionner un produit --',
        allowClear: true,
        language: {
            noResults: function() {
                return "Aucun produit trouvé";
            },
            searching: function() {
                return "Recherche en cours...";
            }
        },
        width: '100%'
    });

    const produitSelect = document.getElementById('produit_id');
    const quantiteInput = document.getElementById('quantite');
    const stockAvailable = document.getElementById('stock-available');
    const totalPriceDisplay = document.getElementById('total-price');
    const motifSelect = document.getElementById('motif_sortie');
    const autreMotifContainer = document.getElementById('autre_motif_container');

    // Fonction pour calculer le prix total avec animation
    function calculateTotalPrice() {
        const selectedOption = produitSelect.options[produitSelect.selectedIndex];
        if (produitSelect.selectedIndex > 0) {
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const quantity = parseInt(quantiteInput.value) || 0;
            const total = price * quantity;
            
            // Animation
            totalPriceDisplay.classList.add('updating');
            setTimeout(() => totalPriceDisplay.classList.remove('updating'), 300);
            
            totalPriceDisplay.textContent = new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(total) + ' DH';
        } else {
            totalPriceDisplay.textContent = '0.00 DH';
        }
    }

    // Fonction pour mettre à jour le stock disponible
    function updateStockAvailable() {
        if (produitSelect.selectedIndex > 0) {
            const selectedOption = produitSelect.options[produitSelect.selectedIndex];
            const maxQuantite = parseInt(selectedOption.dataset.quantite);
            quantiteInput.max = maxQuantite;
            
            const stockColor = maxQuantite <= 5 ? 'text-danger' : 'text-info';
            stockAvailable.innerHTML = `<i class="fas fa-warehouse me-1"></i> Stock disponible: <strong class="${stockColor}">${maxQuantite}</strong> unité(s)`;
            
            if (parseInt(quantiteInput.value) > maxQuantite) {
                quantiteInput.value = maxQuantite;
            }
            calculateTotalPrice();
        } else {
            stockAvailable.textContent = '';
            totalPriceDisplay.textContent = '0.00 DH';
        }
    }

    // Événements Select2
    $('#produit_id').on('select2:select', updateStockAvailable);
    $('#produit_id').on('select2:clear', function() {
        stockAvailable.textContent = '';
        totalPriceDisplay.textContent = '0.00 DH';
    });

    // Événement pour la quantité
    quantiteInput.addEventListener('input', function() {
        const selectedOption = produitSelect.options[produitSelect.selectedIndex];
        if (produitSelect.selectedIndex > 0) {
            const maxQuantite = parseInt(selectedOption.dataset.quantite);
            if (parseInt(this.value) > maxQuantite) {
                this.value = maxQuantite;
            }
        }
        calculateTotalPrice();
    });

    // Initialiser au chargement
    updateStockAvailable();

    // Gestion du champ "Autre motif"
    motifSelect.addEventListener('change', function() {
        if (this.value === 'Autre') {
            autreMotifContainer.classList.remove('d-none');
        } else {
            autreMotifContainer.classList.add('d-none');
        }
    });

    // Déclencher l'événement change au chargement si "Autre" est sélectionné
    if (motifSelect.value === 'Autre') {
        motifSelect.dispatchEvent(new Event('change'));
    }

    // Validation du formulaire
    const form = document.getElementById('sortieForm');
    form.addEventListener('submit', function(e) {
        const produitId = produitSelect.value;
        const quantite = parseInt(quantiteInput.value);
        const nomClient = document.getElementById('nom_client').value.trim();
        const motif = motifSelect.value;

        if (!produitId) {
            e.preventDefault();
            alert('Veuillez sélectionner un produit');
            produitSelect.focus();
            return;
        }

        if (!quantite || quantite <= 0) {
            e.preventDefault();
            alert('Veuillez entrer une quantité valide');
            quantiteInput.focus();
            return;
        }

        const selectedOption = produitSelect.options[produitSelect.selectedIndex];
        const maxQuantite = parseInt(selectedOption.dataset.quantite);
        if (quantite > maxQuantite) {
            e.preventDefault();
            alert(`La quantité ne peut pas dépasser le stock disponible (${maxQuantite})`);
            quantiteInput.focus();
            return;
        }

        if (!nomClient) {
            e.preventDefault();
            alert('Veuillez entrer le nom du client');
            document.getElementById('nom_client').focus();
            return;
        }

        if (!motif) {
            e.preventDefault();
            alert('Veuillez sélectionner un motif de sortie');
            motifSelect.focus();
            return;
        }
    });
});
</script>
@endsection