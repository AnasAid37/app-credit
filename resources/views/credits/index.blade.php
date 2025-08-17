{{-- resources/views/credits/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Crédits')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Crédits Pneumatique aqabli</h1>
        <a href="{{ route('credits.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
            Nouveau Crédit
        </a>
    </div>

    {{-- Barre de recherche --}}
    <div class="mb-6">
        <div class="relative">
            <input type="text" 
                   id="search-input"
                   placeholder="Rechercher par nom du client ou raison..."
                   class="w-full px-4 py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <div id="search-results-count" class="mt-2 text-sm text-gray-600 hidden">
            Résultats trouvés: <span id="results-count">0</span>
        </div>
    </div>

    {{-- Tableau des crédits --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raison</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="credits-table-body" class="bg-white divide-y divide-gray-200">
                    @include('credits.partials.credit-rows', ['credits' => $credits])
                </tbody>
            </table>
        </div>
    </div>

    {{-- Loading indicator --}}
    <div id="loading-indicator" class="hidden text-center py-4">
        <div class="inline-flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Recherche en cours...
        </div>
    </div>
</div>

{{-- Script AJAX pour la recherche instantanée --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const tableBody = document.getElementById('credits-table-body');
    const loadingIndicator = document.getElementById('loading-indicator');
    const searchResultsCount = document.getElementById('search-results-count');
    const resultsCount = document.getElementById('results-count');
    
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Annuler la recherche précédente
        clearTimeout(searchTimeout);
        
        // Délai de 300ms pour éviter trop de requêtes
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    function performSearch(query) {
        // Afficher l'indicateur de chargement
        loadingIndicator.classList.remove('hidden');
        
        // Effectuer la requête AJAX
        fetch(`{{ route('credits.search') }}?search=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Mettre à jour le tableau
            tableBody.innerHTML = data.html;
            
            // Afficher le nombre de résultats
            if (query.length > 0) {
                resultsCount.textContent = data.count;
                searchResultsCount.classList.remove('hidden');
            } else {
                searchResultsCount.classList.add('hidden');
            }
            
            // Masquer l'indicateur de chargement
            loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Erreur lors de la recherche:', error);
            loadingIndicator.classList.add('hidden');
            
            // Afficher un message d'erreur
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-red-500">
                        Erreur lors de la recherche. Veuillez réessayer.
                    </td>
                </tr>
            `;
        });
    }
});
</script>
@endsection