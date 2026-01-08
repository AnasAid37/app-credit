@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- En-tête --}}
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="mb-1">🏷️ Gestion des Catégories</h1>
                    <p class=" mb-0">Organisez les produits par catégories</p>
                </div>
                <a href="{{ route('categories.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nouvelle Catégorie
                </a>
            </div>

        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Grille des catégories --}}
        <div class="row g-3">
            @forelse($categories as $category)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card category-card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            {{-- Icône et couleur --}}
                            <div class="category-icon mb-3" style="background: {{ $category->couleur }}15">
                                <i class="{{ $category->icone }} fa-2x" style="color: {{ $category->couleur }}"></i>
                            </div>

                            {{-- Nom de la catégorie --}}
                            <h5 class="mb-2">{{ $category->nom }}</h5>

                            @if ($category->description)
                                <p class="text-muted small mb-3">{{ Str::limit($category->description, 60) }}</p>
                            @endif

                            {{-- Statistiques --}}
                            <div class="category-stats mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Produits</span>
                                    <strong class="badge bg-primary">{{ $category->products_count }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Valeur du stock</span>
                                    <strong class="text-success">{{ number_format($category->total_stock_value) }}
                                        DH</strong>
                                </div>
                            </div>

                            {{-- Statut --}}
                            <div class="mb-3">
                                @if ($category->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif

                                @if ($category->low_stock_products > 0)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $category->low_stock_products }} alerte
                                    </span>
                                @endif
                            </div>

                            {{-- Boutons --}}
                            <div class="d-flex gap-2">
                                <a href="{{ route('categories.show', $category) }}"
                                    class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="fas fa-eye"></i>
                                    Voir
                                </a>
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Êtes-vous sûr ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune catégorie pour le moment</h5>
                            <p class="text-muted mb-3">Commencez par ajouter des catégories pour organiser vos produits</p>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>
                                Ajouter la première catégorie
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>

    <style>
        :root {
            
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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

            .category-card {
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .category-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            }

            .category-icon {
                width: 70px;
                height: 70px;
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .category-stats {
                padding: 10px;
                background: #f8f9fa;
                border-radius: 8px;
            }
    </style>
@endsection
