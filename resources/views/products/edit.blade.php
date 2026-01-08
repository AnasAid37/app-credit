@extends('layouts.app')

@section('title', 'Modifier un produit')

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
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 1.5rem;
        }

        .info-box {
            background: linear-gradient(135deg, rgba(100, 116, 139, 0.05) 0%, rgba(71, 85, 105, 0.05) 100%);
            border: 2px solid rgba(100, 116, 139, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-box h6 {
            color: #64748b;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.625rem 0;
            border-bottom: 1px solid rgba(100, 116, 139, 0.1);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: #64748b;
            font-size: 0.875rem;
        }

        .info-value {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.9375rem;
        }

        .status-box {
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid;
        }

        .status-box.success {
            background: rgba(16, 185, 129, 0.05);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .status-box.warning {
            background: rgba(245, 158, 11, 0.05);
            border-color: rgba(245, 158, 11, 0.2);
        }

        .status-box.danger {
            background: rgba(239, 68, 68, 0.05);
            border-color: rgba(239, 68, 68, 0.2);
        }

        .status-box h6 {
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .status-box.success h6 {
            color: var(--success-color);
        }

        .status-box.warning h6 {
            color: var(--warning-color);
        }

        .status-box.danger h6 {
            color: var(--danger-color);
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

        .btn-modern-warning {
            background: var(--warning-color);
            color: white;
        }

        .btn-modern-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.4);
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

        .change-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            margin-left: 0.5rem;
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
                        <i class="fas fa-edit me-2"></i>
                        Modifier un produit
                    </h1>
                    <p class="mb-0 opacity-90">Modifier les informations du produit</p>
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
                <!-- Info actuelle -->
                <div class="info-box">
                    <h6>
                        <i class="fas fa-info-circle"></i>
                        Informations actuelles
                    </h6>
                    <div>
                        <div class="info-item">
                            <span class="info-label">Produit:</span>
                            <span class="info-value">
                                {{ $product->marque ?? 'N/A' }}
                                <span class="text-muted">({{ $product->taille }})</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Prix:</span>
                            <span class="info-value text-success">{{ number_format($product->price, 2) }} DH</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Stock actuel:</span>
                            <span class="info-value">{{ $product->quantite }} unité(s)</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Seuil d'alerte:</span>
                            <span class="info-value text-warning">{{ $product->seuil_alerte }} unité(s)</span>
                        </div>
                    </div>
                </div>

                <div class="modern-card">
                    <form method="POST" action="{{ route('products.update', $product->id) }}" id="editForm">
                        @csrf
                        @method('PUT')

                        <!-- Section Informations de base -->
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informations de base
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="category_id" class="form-select">
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach (auth()->user()->categories()->active()->get() as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
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
                                        value="{{ old('price', $product->price) }}" data-original="{{ $product->price }}"
                                        required>
                                    <span class="input-group-text">DH</span>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <span class="change-indicator d-none" id="price-change">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Modifié
                                </span>
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
                                        id="taille" name="taille" value="{{ old('taille', $product->taille) }}"
                                        data-original="{{ $product->taille }}" required>
                                    @error('taille')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <span class="change-indicator d-none" id="taille-change">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Modifié
                                </span>
                            </div>

                            <div class="col-12">
                                <label for="marque" class="form-label">Marque</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-copyright"></i>
                                    </span>
                                    <input type="text" class="form-control @error('marque') is-invalid @enderror"
                                        id="marque" name="marque" value="{{ old('marque', $product->marque) }}"
                                        data-original="{{ $product->marque }}">
                                    @error('marque')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <span class="change-indicator d-none" id="marque-change">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Modifié
                                </span>
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
                                <label for="quantite" class="form-label">Quantité</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-cubes"></i>
                                    </span>
                                    <input type="number" class="form-control @error('quantite') is-invalid @enderror"
                                        id="quantite" name="quantite" min="0"
                                        value="{{ old('quantite', $product->quantite) }}"
                                        data-original="{{ $product->quantite }}">
                                    @error('quantite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Stock actuel: {{ $product->quantite }} unité(s)
                                </div>
                                <span class="change-indicator d-none" id="quantite-change">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Modifié
                                </span>
                            </div>

                            <div class="col-md-6">
                                <label for="seuil_alerte" class="form-label">Seuil d'alerte</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                    <input type="number" class="form-control @error('seuil_alerte') is-invalid @enderror"
                                        id="seuil_alerte" name="seuil_alerte" min="1"
                                        value="{{ old('seuil_alerte', $product->seuil_alerte) }}"
                                        data-original="{{ $product->seuil_alerte }}">
                                    @error('seuil_alerte')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-bell"></i>
                                    Alerte si stock ≤ cette valeur
                                </div>
                                <span class="change-indicator d-none" id="seuil_alerte-change">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Modifié
                                </span>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- État du stock -->
                        @php
                            $stockClass = 'success';
                            $stockIcon = 'check-circle';
                            $stockText = 'En stock';
                            $stockBadge = 'success';

                            if ($product->quantite == 0) {
                                $stockClass = 'danger';
                                $stockIcon = 'times-circle';
                                $stockText = 'Rupture de stock';
                                $stockBadge = 'danger';
                            } elseif ($product->quantite <= $product->seuil_alerte) {
                                $stockClass = 'warning';
                                $stockIcon = 'exclamation-triangle';
                                $stockText = 'Stock faible';
                                $stockBadge = 'warning';
                            }
                        @endphp

                        <div class="status-box {{ $stockClass }}">
                            <h6>
                                <i class="fas fa-chart-line"></i>
                                État du stock
                            </h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="info-label">Statut actuel:</span>
                                <span class="badge-modern badge-{{ $stockBadge }}">
                                    <i class="fas fa-{{ $stockIcon }}"></i>
                                    {{ $stockText }}
                                </span>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('products.index') }}" class="btn btn-modern-outline">
                                <i class="fas fa-times"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-modern-warning" id="submitBtn">
                                <i class="fas fa-save"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Détection des changements
            const fields = ['price', 'taille', 'marque', 'quantite', 'seuil_alerte'];
            const submitBtn = document.getElementById('submitBtn');
            let hasChanges = false;

            fields.forEach(field => {
                const input = document.getElementById(field);
                const changeIndicator = document.getElementById(field + '-change');

                if (input && changeIndicator) {
                    input.addEventListener('input', function() {
                        const original = this.getAttribute('data-original');
                        const current = this.value;

                        if (current != original) {
                            changeIndicator.classList.remove('d-none');
                            hasChanges = true;
                        } else {
                            changeIndicator.classList.add('d-none');
                        }

                        // Vérifier si au moins un champ a changé
                        updateSubmitButton();
                    });
                }
            });

            function updateSubmitButton() {
                hasChanges = fields.some(field => {
                    const input = document.getElementById(field);
                    if (input) {
                        return input.value != input.getAttribute('data-original');
                    }
                    return false;
                });
            }

            // Validation avant soumission
            const form = document.getElementById('editForm');
            form.addEventListener('submit', function(e) {
                const price = parseFloat(document.getElementById('price').value);
                const taille = document.getElementById('taille').value.trim();

                if (!price || price <= 0) {
                    e.preventDefault();
                    alert('Veuillez entrer un prix valide');
                    document.getElementById('price').focus();
                    return;
                }

                if (!taille) {
                    e.preventDefault();
                    alert('Veuillez entrer une taille');
                    document.getElementById('taille').focus();
                    return;
                }

                if (!hasChanges) {
                    e.preventDefault();
                    alert('Aucune modification détectée');
                    return;
                }
            });

            // Confirmation si l'utilisateur quitte sans sauvegarder
            window.addEventListener('beforeunload', function(e) {
                if (hasChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            // Annuler la confirmation si le formulaire est soumis
            form.addEventListener('submit', function() {
                window.removeEventListener('beforeunload', arguments.callee);
            });
        });
    </script>
@endsection
