{{-- resources/views/credits/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier Crédit')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête avec animation -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center bg-white rounded-2xl shadow-lg px-8 py-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            Modifier Crédit #{{ $credit->id }}
                        </h1>
                        <p class="text-gray-500 text-sm">Mettre à jour les informations du crédit</p>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('credits.show', $credit) }}" 
                       class="inline-flex items-center bg-green-100 hover:bg-green-200 text-green-700 px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Voir
                    </a>
                    <a href="{{ route('credits.index') }}" 
                       class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulaire principal -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-600 to-red-600 px-8 py-6">
                            <h2 class="text-xl font-semibold text-white">Modifier les informations</h2>
                        </div>

                        <form action="{{ route('credits.update', $credit) }}" method="POST" class="p-8">
                            @csrf
                            @method('PUT')

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
                                               value="{{ old('client_name', $credit->client->name) }}"
                                               class="w-full px-4 py-3 border-2 @error('client_name') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300">
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
                                               value="{{ old('client_phone', $credit->client->phone) }}"
                                               class="w-full px-4 py-3 border-2 @error('client_phone') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300">
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
                                              class="w-full px-4 py-3 border-2 @error('client_address') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 resize-none">{{ old('client_address', $credit->client->address) }}</textarea>
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
                                                   value="{{ old('amount', $credit->amount) }}"
                                                   onchange="calculateRemaining()"
                                                   class="w-full px-4 py-3 pr-12 border-2 @error('amount') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300">
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
                                        <label for="status" class="block text-sm font-semibold text-gray-700">
                                            Statut <span class="text-red-500">*</span>
                                        </label>
                                        <select id="status" 
                                                name="status"
                                                class="w-full px-4 py-3 border-2 @error('status') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300">
                                            <option value="active" {{ old('status', $credit->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                            <option value="paid" {{ old('status', $credit->status) === 'paid' ? 'selected' : '' }}>Payé</option>
                                            <option value="cancelled" {{ old('status', $credit->status) === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                        </select>
                                        @error('status')
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
                                    <label for="reason" class="block text-sm font-semibold text-gray-700">
                                        Raison du crédit
                                    </label>
                                    <textarea id="reason" 
                                              name="reason" 
                                              rows="4"
                                              class="w-full px-4 py-3 border-2 @error('reason') border-red-300 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 resize-none">{{ old('reason', $credit->reason) }}</textarea>
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

                            <!-- Information de création -->
                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-xl p-6 mb-8">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-800">Informations de création</h4>
                                </div>
                                <div class="space-y-2 text-sm text-gray-700">
                                    <p><span class="font-medium">Créé par:</span> {{ $credit->creator->name }}</p>
                                    <p><span class="font-medium">Date de création:</span> {{ $credit->created_at->format('d/m/Y à H:i') }}</p>
                                    @if($credit->updated_at != $credit->created_at)
                                        <p><span class="font-medium">Dernière modification:</span> {{ $credit->updated_at->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('credits.index') }}" 
                                   class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-4 focus:ring-gray-100 transition-all duration-300 font-semibold">
                                    Annuler
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-4 focus:ring-orange-100 transition-all duration-300 font-semibold transform hover:scale-105 shadow-lg">
                                    Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar - Ajout de paiement -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden sticky top-8">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Ajouter un paiement
                            </h3>
                        </div>

                        <div class="p-6">
                            <!-- Résumé actuel -->
                            <div class="mb-6 space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">Total:</span>
                                    <span class="font-bold text-gray-900" id="total_amount">{{ number_format($credit->amount, 2) }} DH</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                    <span class="text-sm font-medium text-green-600">Payé:</span>
                                    <span class="font-bold text-green-700">{{ number_format($credit->paid_amount, 2) }} DH</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                                    <span class="text-sm font-medium text-orange-600">Restant:</span>
                                    <span class="font-bold text-orange-700" id="remaining_amount">{{ number_format($credit->remaining_amount, 2) }} DH</span>
                                </div>
                            </div>

                            <!-- Formulaire de paiement -->
                            <form action="{{ route('credits.add-payment', $credit) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="space-y-2">
                                    <label for="payment_amount" class="block text-sm font-semibold text-gray-700">
                                        Montant du paiement (DH)
                                    </label>
                                    <div class="relative">
                                        <input type="number" 
                                               id="payment_amount" 
                                               name="payment_amount" 
                                               step="0.01" 
                                               min="0.01" 
                                               max="{{ $credit->remaining_amount }}"
                                               class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300"
                                               placeholder="0.00">
                                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">DH</span>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="payment_notes" class="block text-sm font-semibold text-gray-700">
                                        Notes (optionnel)
                                    </label>
                                    <textarea id="payment_notes" 
                                              name="payment_notes" 
                                              rows="2"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300 resize-none"
                                              placeholder="Notes sur le paiement"></textarea>
                                </div>

                                <button type="submit" 
                                        class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-100 transition-all duration-300 font-semibold transform hover:scale-105 shadow-lg">
                                    Enregistrer le paiement
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateRemaining() {
    const totalAmount = parseFloat(document.getElementById('amount').value) || 0;
    const paidAmount = {{ $credit->paid_amount }};
    const remaining = Math.max(0, totalAmount - paidAmount);
    
    document.getElementById('total_amount').textContent = 
        new Intl.NumberFormat('fr-FR', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        }).format(totalAmount) + ' DH';
        
    document.getElementById('remaining_amount').textContent = 
        new Intl.NumberFormat('fr-FR', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        }).format(remaining) + ' DH';
        
    // Mettre à jour le max du champ de paiement
    const paymentInput = document.getElementById('payment_amount');
    if (paymentInput) {
        paymentInput.max = remaining;
    }
}

// Calculer au chargement de la page
document.addEventListener('DOMContentLoaded', calculateRemaining);
</script>
@endsection