{{-- resources/views/credits/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nouveau Crédit')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête avec animation -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center bg-white rounded-2xl shadow-lg px-8 py-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            Nouveau Crédit
                        </h1>
                        <p class="text-gray-500 text-sm">Créer un nouveau crédit client</p>
                    </div>
                </div>
                
                <a href="{{ route('credits.index') }}" 
                   class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la liste
                </a>
            </div>

            <!-- Formulaire principal -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                    <h2 class="text-xl font-semibold text-white">Informations du crédit</h2>
                </div>

                <form action="{{ route('credits.store') }}" method="POST" class="p-8">
                    @csrf

                    <!-- Section Client -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Informations du client</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="client_name" class="block text-sm font-semibold text-gray-700">
                                    Nom du client <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="client_name" 
                                       name="client_name" 
                                       value="{{ old('client_name') }}"
                                       class="w-full px-4 py-3 border-2 @error('client_name') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300"
                                       placeholder="Nom complet du client">
                                @error('client_name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="client_phone" class="block text-sm font-semibold text-gray-700">
                                    Téléphone
                                </label>
                                <input type="tel" 
                                       id="client_phone" 
                                       name="client_phone" 
                                       value="{{ old('client_phone') }}"
                                       class="w-full px-4 py-3 border-2 @error('client_phone') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300"
                                       placeholder="0612345678">
                                @error('client_phone')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <label for="client_address" class="block text-sm font-semibold text-gray-700">
                                Adresse
                            </label>
                            <textarea id="client_address" 
                                      name="client_address" 
                                      rows="3"
                                      class="w-full px-4 py-3 border-2 @error('client_address') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 resize-none"
                                      placeholder="Adresse du client (optionnel)">{{ old('client_address') }}</textarea>
                            @error('client_address')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Section Crédit -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Détails du crédit</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="amount" class="block text-sm font-semibold text-gray-700">
                                    Montant total (DH) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           id="amount" 
                                           name="amount" 
                                           step="0.01" 
                                           min="0.01" 
                                           max="999999.99"
                                           value="{{ old('amount') }}"
                                           onchange="calculateRemaining()"
                                           class="w-full px-4 py-3 pr-12 border-2 @error('amount') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300"
                                           placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">DH</span>
                                </div>
                                @error('amount')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="paid_amount" class="block text-sm font-semibold text-gray-700">
                                    Montant payé initialement (DH)
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           id="paid_amount" 
                                           name="paid_amount" 
                                           step="0.01" 
                                           min="0" 
                                           value="{{ old('paid_amount', 0) }}"
                                           onchange="calculateRemaining()"
                                           class="w-full px-4 py-3 pr-12 border-2 @error('paid_amount') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300"
                                           placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">DH</span>
                                </div>
                                @error('paid_amount')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Affichage du montant restant -->
                        <div class="mt-6">
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 border-2 border-orange-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-orange-700">Montant restant</p>
                                        <p class="text-2xl font-bold text-orange-800" id="remaining_display">0.00 DH</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <label for="reason" class="block text-sm font-semibold text-gray-700">
                                Raison du crédit
                            </label>
                            <textarea id="reason" 
                                      name="reason" 
                                      rows="4"
                                      class="w-full px-4 py-3 border-2 @error('reason') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 resize-none"
                                      placeholder="Décrivez la raison du crédit (optionnel)">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                        <a href="{{ route('credits.index') }}" 
                           class="px-8 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-4 focus:ring-gray-100 transition-all duration-300 font-semibold">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-all duration-300 font-semibold transform hover:scale-105 shadow-lg">
                            Créer le crédit
                        </button>
                    </div>
                </form>
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