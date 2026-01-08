@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- En-tête --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Modifier la Catégorie</h3>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nom de la catégorie --}}
                        <div class="mb-3">
                            <label class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="nom" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   value="{{ old('nom', $category->nom) }}"
                                   placeholder="Ex: Pneus, Électronique, Vêtements..."
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Description brève de la catégorie (optionnel)">{{ old('description', $category->description) }}</textarea>
                        </div>

                        {{-- Couleur --}}
                        <div class="mb-3">
                            <label class="form-label">Couleur distinctive <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" 
                                       name="couleur" 
                                       id="colorPicker"
                                       class="form-control form-control-color" 
                                       value="{{ old('couleur', $category->couleur) }}"
                                       required>
                                <input type="text" 
                                       id="colorValue"
                                       class="form-control" 
                                       value="{{ old('couleur', $category->couleur) }}"
                                       readonly>
                            </div>
                            <small class="text-muted">Choisissez une couleur distinctive pour identifier facilement la catégorie</small>
                        </div>

                        {{-- Icône --}}
                        <div class="mb-4">
                            <label class="form-label">Icône <span class="text-danger">*</span></label>
                            <input type="hidden" 
                                   name="icone" 
                                   id="selectedIcon" 
                                   value="{{ old('icone', $category->icone) }}">
                            
                            {{-- Aperçu de l'icône --}}
                            <div class="icon-preview mb-3 text-center p-4 bg-light rounded">
                                <i id="iconPreview" 
                                   class="{{ old('icone', $category->icone) }} fa-4x"
                                   style="color: {{ old('couleur', $category->couleur) }}"></i>
                            </div>

                            {{-- Sélection des icônes --}}
                            <div class="icons-grid">
                                @php
                                $icons = [
                                    'fas fa-box', 'fas fa-boxes', 'fas fa-cube', 'fas fa-cubes',
                                    'fas fa-shopping-cart', 'fas fa-store', 'fas fa-warehouse',
                                    'fas fa-tshirt', 'fas fa-shoe-prints', 'fas fa-ring',
                                    'fas fa-laptop', 'fas fa-mobile-alt', 'fas fa-tv',
                                    'fas fa-car', 'fas fa-motorcycle', 'fas fa-bicycle',
                                    'fas fa-hammer', 'fas fa-wrench', 'fas fa-tools',
                                    'fas fa-couch', 'fas fa-chair', 'fas fa-bed',
                                    'fas fa-apple-alt', 'fas fa-coffee', 'fas fa-utensils',
                                    'fas fa-book', 'fas fa-graduation-cap', 'fas fa-pen',
                                    'fas fa-heart', 'fas fa-star', 'fas fa-flag',
                                ];
                                @endphp

                                @foreach($icons as $icon)
                                <div class="icon-option" data-icon="{{ $icon }}">
                                    <i class="{{ $icon }} fa-2x"></i>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Statut d'activité --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="actif" 
                                       id="actif"
                                       value="1"
                                       {{ old('actif', $category->actif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">
                                    Catégorie active
                                </label>
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-save me-2"></i>
                                Mettre à jour
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.icons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
    gap: 10px;
    max-height: 300px;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.icon-option {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}

.icon-option:hover {
    border-color: #6366f1;
    background: #6366f115;
    transform: scale(1.1);
}

.icon-option.selected {
    border-color: #6366f1;
    background: #6366f125;
    box-shadow: 0 0 0 3px #6366f125;
}

.icon-preview {
    border: 2px dashed #dee2e6;
}
</style>

<script>
// Changement de couleur
const colorPicker = document.getElementById('colorPicker');
const colorValue = document.getElementById('colorValue');
const iconPreview = document.getElementById('iconPreview');

colorPicker.addEventListener('input', (e) => {
    colorValue.value = e.target.value;
    iconPreview.style.color = e.target.value;
});

// Sélection d'icône
const iconOptions = document.querySelectorAll('.icon-option');
const selectedIcon = document.getElementById('selectedIcon');

iconOptions.forEach(option => {
    // Sélectionner l'icône préalablement choisie
    if (option.dataset.icon === selectedIcon.value) {
        option.classList.add('selected');
    }

    option.addEventListener('click', () => {
        // Supprimer la sélection précédente
        iconOptions.forEach(opt => opt.classList.remove('selected'));
        
        // Sélectionner la nouvelle
        option.classList.add('selected');
        selectedIcon.value = option.dataset.icon;
        
        // Mettre à jour l'aperçu
        iconPreview.className = option.dataset.icon + ' fa-4x';
        iconPreview.style.color = colorPicker.value;
    });
});
</script>
@endsection