{{-- resources/views/credits/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Détails du Crédit')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Crédit #{{ $credit->id }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('credits.edit', $credit) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Modifier
                </a>
                <a href="{{ route('credits.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Retour à la liste
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Informations principales --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations du crédit</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Montant</label>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($credit->amount, 2) }} DH</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Statut</label>
                        @switch($credit->status)
                            @case('active')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Actif
                                </span>
                                @break
                            @case('paid')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Payé
                                </span>
                                @break
                            @case('cancelled')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Annulé
                                </span>
                                @break
                        @endswitch
                    </div>

                    @if($credit->reason)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Raison</label>
                            <p class="text-gray-900">{{ $credit->reason }}</p>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 pt-4">
                        <label class="block text-sm font-medium text-gray-500">Date de création</label>
                        <p class="text-gray-900">{{ $credit->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($credit->updated_at != $credit->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dernière modification</label>
                            <p class="text-gray-900">{{ $credit->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Créé par</label>
                        <p class="text-gray-900">{{ $credit->creator->name }}</p>
                    </div>
                </div>
            </div>

            {{-- Informations client --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations du client</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nom</label>
                        <p class="text-lg font-medium text-gray-900">{{ $credit->client->name }}</p>
                    </div>

                    @if($credit->client->phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="text-gray-900">
                                <a href="tel:{{ $credit->client->phone }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $credit->client->phone }}
                                </a>
                            </p>
                        </div>
                    @endif

                    @if($credit->client->address)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Adresse</label>
                            <p class="text-gray-900">{{ $credit->client->address }}</p>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 pt-4">
                        <label class="block text-sm font-medium text-gray-500">Client depuis</label>
                        <p class="text-gray-900">{{ $credit->client->created_at->format('d/m/Y') }}</p>
                    </div>

                    {{-- Autres crédits du client --}}
                    @php
                        $otherCredits = $credit->client->credits()->where('id', '!=', $credit->id)->orderBy('created_at', 'desc')->limit(5)->get();
                    @endphp

                    @if($otherCredits->count() > 0)
                        <div class="border-t border-gray-200 pt-4">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Autres crédits</label>
                            <div class="space-y-2">
                                @foreach($otherCredits as $otherCredit)
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">{{ $otherCredit->created_at->format('d/m/Y') }}</span>
                                        <span class="font-medium">{{ number_format($otherCredit->amount, 2) }} DH</span>
                                        <span class="px-2 py-1 text-xs rounded 
                                            @if($otherCredit->status === 'active') bg-yellow-100 text-yellow-800
                                            @elseif($otherCredit->status === 'paid') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($otherCredit->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('credits.destroy', $credit) }}" 
                      method="POST" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce crédit ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        Supprimer ce crédit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection