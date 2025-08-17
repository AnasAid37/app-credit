{{-- resources/views/credits/partials/credit-rows.blade.php --}}
@forelse($credits as $credit)
<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">{{ $credit->client->name }}</div>
        @if($credit->client->phone)
            <div class="text-sm text-gray-500">{{ $credit->client->phone }}</div>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-bold text-gray-900">{{ number_format($credit->amount, 2) }} DH</div>
    </td>
    <td class="px-6 py-4">
        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $credit->reason ?: 'N/A' }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @switch($credit->status)
            @case('active')
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Actif
                </span>
                @break
            @case('paid')
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    Payé
                </span>
                @break
            @case('cancelled')
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                    Annulé
                </span>
                @break
        @endswitch
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $credit->created_at->format('d/m/Y') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <div class="flex space-x-2">
            <a href="{{ route('credits.edit', $credit) }}" 
               class="text-blue-600 hover:text-blue-900 transition duration-200">
                Modifier
            </a>
            <form action="{{ route('credits.destroy', $credit) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce crédit ?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="text-red-600 hover:text-red-900 transition duration-200">
                    Supprimer
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
        @if(request('search'))
            Aucun résultat trouvé pour "{{ request('search') }}"
        @else
            Aucun crédit enregistré
        @endif
    </td>
</tr>
@endforelse