@extends('layouts.app')

@section('title', 'Ajouter un produit')

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

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.9375rem;
        }

        .form-control:focus,
        .form-select:focus {
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
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-right: none;
            color: #64748b;
            font-weight: 500;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .form-text {
            color: #64748b;
            font-size: 0.8125rem;
            margin-top: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
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

        .preview-box {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
            border: 2px solid rgba(99, 102, 241, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .preview-box h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        }

        .preview-item:last-child {
            border-bottom: none;
        }

        .preview-label {
            font-weight: 500;
            color: #64748b;
            font-size: 0.875rem;
        }

        .preview-value {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.9375rem;
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

        /* Animation pour les champs qui changent */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }
        }

        .preview-value.updated {
            animation: pulse 0.3s ease;
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .modern-card {
                padding: 1.5rem;
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
                        <i class="fas fa-plus-circle me-2"></i>
                        Ajouter un produit
                    </h1>
                    <p class="mb-0 opacity-90">Ajouter un nouveau produit au stock</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
            </div>
        </div>

        <!-- Formulaire principal -->
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="modern-card">
                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf

                        <!-- Section Informations de base -->
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informations de base
                        </div>
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">Information importante</h6>
                                    <p class="mb-0">
                                        si vous ajoutez un produit qui existe déjà (même marque et taille), le système
                                        mettra à jour
                                        simplement la quantité en stock au lieu de créer une nouvelle entrée.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="category_id" class="form-select">
                                <option value="">-- Sélectionnez une catégorie --</option>
                                @foreach (\App\Models\Category::where('user_id', auth()->id())->where('actif', true)->orderBy('nom')->get() as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        <i class="{{ $cat->icone }}"></i> {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Vous pouvez gérer les catégories dans la section
                                <a href="{{ route('categories.create') }}" target="_blank">Ajouter une nouvelle catégorie</a>
                            </small>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="price" class="form-label">
                                    Prix du produit (DH) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" step="0.01" min="0"
                                        value="{{ old('price') }}" placeholder="0.00" required>
                                    <span class="input-group-text">DH</span>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="taille" class="form-label">
                                    Taille <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-ruler"></i>
                                    </span>
                                    <input type="text" class="form-control @error('taille') is-invalid @enderror"
                                        id="taille" name="taille" placeholder="Ex: 205/55 R16"
                                        value="{{ old('taille') }}" required>
                                    @error('taille')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="marque" class="form-label">Marque</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-copyright"></i>
                                    </span>
                                    <input type="text" class="form-control @error('marque') is-invalid @enderror"
                                        id="marque" name="marque" placeholder="Ex: Michelin, Bridgestone..."
                                        value="{{ old('marque') }}">
                                    @error('marque')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Optionnel - Laissez vide si non applicable
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- Section Gestion du stock -->
                        <div class="section-title">
                            <i class="fas fa-warehouse"></i>
                            Gestion du stock
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="quantite" class="form-label">Quantité initiale</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-cubes"></i>
                                    </span>
                                    <input type="number" class="form-control @error('quantite') is-invalid @enderror"
                                        id="quantite" name="quantite" min="0" placeholder="0">
                                    @error('quantite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="seuil_alerte" class="form-label">Seuil d'alerte</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                    <input type="number" class="form-control @error('seuil_alerte') is-invalid @enderror"
                                        id="seuil_alerte" name="seuil_alerte" min="1"
                                        value="{{ old('seuil_alerte', 5) }}" placeholder="5">
                                    @error('seuil_alerte')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-bell"></i>
                                    Une alerte sera générée lorsque la quantité sera inférieure ou égale à cette valeur
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- Aperçu du produit -->
                        <div class="preview-box">
                            <h6>
                                <i class="fas fa-eye"></i>
                                Aperçu du produit
                            </h6>
                            <div id="product-preview">
                                <div class="preview-item">
                                    <span class="preview-label">Produit:</span>
                                    <span class="preview-value">
                                        <span id="preview-marque">N/A</span>
                                        <span class="text-muted">(<span id="preview-taille">-</span>)</span>
                                    </span>
                                </div>
                                <div class="preview-item">
                                    <span class="preview-label">Prix:</span>
                                    <span class="preview-value text-success">
                                        <span id="preview-price">0.00</span> DH
                                    </span>
                                </div>
                                <div class="preview-item">
                                    <span class="preview-label">Stock initial:</span>
                                    <span class="preview-value">
                                        <span id="preview-quantite">0</span> unité(s)
                                    </span>
                                </div>
                                <div class="preview-item">
                                    <span class="preview-label">Seuil d'alerte:</span>
                                    <span class="preview-value text-warning">
                                        <span id="preview-seuil">5</span> unité(s)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('products.index') }}" class="btn btn-modern-outline">
                                <i class="fas fa-times"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-modern-primary">
                                <i class="fas fa-save"></i>
                                Ajouter le produit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Aperçu en temps réel avec animation
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');
            const tailleInput = document.getElementById('taille');
            const marqueInput = document.getElementById('marque');
            const quantiteInput = document.getElementById('quantite');
            const seuilInput = document.getElementById('seuil_alerte');

            function animateUpdate(element) {
                element.classList.add('updated');
                setTimeout(() => element.classList.remove('updated'), 300);
            }

            function updatePreview() {
                const priceValue = priceInput.value || '0.00';
                const tailleValue = tailleInput.value || '-';
                const marqueValue = marqueInput.value || 'N/A';
                const quantiteValue = quantiteInput.value || '0';
                const seuilValue = seuilInput.value || '5';

                const priceEl = document.getElementById('preview-price');
                const tailleEl = document.getElementById('preview-taille');
                const marqueEl = document.getElementById('preview-marque');
                const quantiteEl = document.getElementById('preview-quantite');
                const seuilEl = document.getElementById('preview-seuil');

                if (priceEl.textContent !== priceValue) {
                    priceEl.textContent = priceValue;
                    animateUpdate(priceEl);
                }

                if (tailleEl.textContent !== tailleValue) {
                    tailleEl.textContent = tailleValue;
                    animateUpdate(tailleEl);
                }

                if (marqueEl.textContent !== marqueValue) {
                    marqueEl.textContent = marqueValue;
                    animateUpdate(marqueEl);
                }

                if (quantiteEl.textContent !== quantiteValue) {
                    quantiteEl.textContent = quantiteValue;
                    animateUpdate(quantiteEl);
                }

                if (seuilEl.textContent !== seuilValue) {
                    seuilEl.textContent = seuilValue;
                    animateUpdate(seuilEl);
                }
            }

            priceInput.addEventListener('input', updatePreview);
            tailleInput.addEventListener('input', updatePreview);
            marqueInput.addEventListener('input', updatePreview);
            quantiteInput.addEventListener('input', updatePreview);
            seuilInput.addEventListener('input', updatePreview);

            // Validation du formulaire
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const price = parseFloat(priceInput.value);
                const taille = tailleInput.value.trim();

                if (!price || price <= 0) {
                    e.preventDefault();
                    priceInput.focus();
                    alert('Veuillez entrer un prix valide');
                    return;
                }

                if (!taille) {
                    e.preventDefault();
                    tailleInput.focus();
                    alert('Veuillez entrer une taille');
                    return;
                }
            });
        });
    </script>
@endsection
