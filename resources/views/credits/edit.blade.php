{{-- resources/views/credits/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier Crédit')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Modifier Crédit #{{ $credit->id }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('credits.show', $credit) }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Voir
                </a>
                <a href="{{ route('credits.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Retour
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('credits.update', $credit) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Informations client --}}
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du client</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom du client <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="client_name" 
                                   name="client_name" 
                                   value="{{ old('client_name', $credit->client->name) }}"
                                   class="w-full px-3 py-2 border @error('client_name') border-red-300 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('client_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Téléphone
                            </label>
                            <input type="tel" 
                                   id="client_phone" 
                                   name="client_phone" 
                                   value="{{ old('client_phone', $credit->client->phone) }}"
                                   class="w-full px-3 py-2 border @error('client_phone') border-red-300 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('client_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="client_address" class="block text-sm font-medium text-gray-700 mb-1">
                            Adresse
                        </label>
                        <textarea id="client_address" 
                                  name="client_address" 
                                  rows="2"
                                  class="w-full px-3 py-2 border @error('client_address') border-red-300 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('client_address', $credit->client->address) }}</textarea>
                        @error('client_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Informations crédit --}}
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Détails du crédit</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Montant (DH) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="amount" 
                                   name="amount" 
                                   step="0.01" 
                                   min="0.01" 
                                   max="999999.99"
                                   value="{{ old('amount', $credit->amount) }}"
                                   class="w-full px-3 py-2 border @error('amount') border-red-300 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status"
                                    class="w-full px-3 py-2 border @error('status') border-red-300 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="active" {{ old('status', $credit->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="paid" {{ old('status', $credit->status) === 'paid' ? 'selected' : '' }}>Payé</option>
                                <option value="cancelled" {{ old('status', $credit->status) === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                            Raison du crédit
                        </label>
                        <textarea id="reason" 
                                  name="reason" 
                                  rows="3"
                                  class="w-full px-3 py-2 border @error('reason') border-red-300 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('reason', $credit->reason) }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Information de création --}}
                <div class="bg-gray-50 p-4 rounded-md">
                    <p class="text-sm text-gray-600">
                        <strong>Créé par:</strong> {{ $credit->creator->name }} <br>
                        <strong>Date de création:</strong> {{ $credit->created_at->format('d/m/Y à H:i') }}
                        @if($credit->updated_at != $credit->created_at)
                            <br><strong>Dernière modification:</strong> {{ $credit->updated_at->format('d/m/Y à H:i') }}
                        @endif
                    </p>
                </div>

                {{-- Boutons --}}
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('credits.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection