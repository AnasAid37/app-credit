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
                        <label class="block text-sm font-medium text-gray-500">Montant payé</label>
                        <p class="text-xl font-bold text-blue-600">{{ number_format($credit->paid_amount, 2) }} DH</p>
                    </div>
                    

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Montant restant</label>
                        <p class="text-xl font-bold {{ $credit->remaining_amount > 0 ? 'text-orange-600' : 'text-green-600' }}">
                            {{ number_format($credit->remaining_amount, 2) }} DH
                        </p>
                    </div>

                    {{-- Barre de progression --}}
                    <div class="pt-2">
                        <div class="flex justify-between text-sm text-gray-500 mb-1">
                            <span>Progression</span>
                            <span>{{ number_format(($credit->paid_amount / $credit->amount) * 100, 2) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full"
                                style="width: {{ ($credit->paid_amount / $credit->amount) * 100 }}%"></div>
                        </div>
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

        {{-- Section des paiements --}}
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Historique des paiements</h2>

                @if($credit->status === 'active' && $credit->remaining_amount > 0)
                <button onclick="openPaymentModal()"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter un paiement
                </button>
                @endif
            </div>

            @if($credit->payments && $credit->payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Méthode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Référence
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Enregistré par
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($credit->payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->created_at->format('d/m/Y à H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                {{ number_format($payment->amount, 2) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->payment_method ?? 'Non spécifié' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->reference ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->creator->name ?? 'Système' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                                Total payé: <span class="text-green-600">{{ number_format($credit->paid_amount, 2) }} DH</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>Aucun paiement enregistré pour ce crédit.</p>
            </div>
            @endif
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

<!-- Modal pour ajouter un paiement -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ajouter un paiement</h3>

        <form action="{{ url('credits/' . $credit->id . '/add-payment') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Montant à payer</label>
                <input type="number"
                    name="payment_amount"
                    step="0.01"
                    min="0.01"
                    max="{{ $credit->remaining_amount }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <p class="text-xs text-gray-500 mt-1">Montant maximum: {{ number_format($credit->remaining_amount, 2) }} DH</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="cash">Espèces</option>
                    <option value="check">Chèque</option>
                    <option value="transfer">Virement</option>
                    <option value="card">Carte bancaire</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Référence (optionnel)</label>
                <input type="text"
                    name="reference"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button"
                    onclick="closePaymentModal()"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Annuler
                </button>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('paymentModal').addEventListener('click', function(e) {
        if (e.target.id === 'paymentModal') {
            closePaymentModal();
        }
    });
</script>
@endsection