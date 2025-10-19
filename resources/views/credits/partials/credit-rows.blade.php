@forelse($credits as $credit)
<tr>
    <td class="px-4 py-3">
        <div class="fw-bold text-gray-900">{{ $credit->client->name }}</div>
        @if($credit->client->phone)
            <div class="text-muted small">{{ $credit->client->phone }}</div>
        @endif
    </td>
    <td class="px-4 py-3">
        <div class="fw-bold text-primary">{{ number_format($credit->amount, 2) }} DH</div>
    </td>
    <td class="px-4 py-3">
        <div class="text-truncate" style="max-width: 200px;" title="{{ $credit->reason }}">
            {{ $credit->reason ?: 'N/A' }}
        </div>
    </td>
    <td class="px-4 py-3">
        @switch($credit->status)
            @case('active')
                <span class="badge bg-warning text-dark">Actif</span>
                @break
            @case('paid')
                <span class="badge bg-success">Payé</span>
                @break
            @case('cancelled')
                <span class="badge bg-danger">Annulé</span>
                @break
        @endswitch
    </td>
    <td class="px-4 py-3">
        {{ $credit->created_at->format('d/m/Y') }}
    </td>
    <td class="px-4 py-3 text-center">
        <div class="btn-group btn-group-sm">
            <a href="{{ route('credits.show', $credit) }}" 
               class="btn btn-info" title="Voir">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('credits.edit', $credit) }}" 
               class="btn btn-warning" title="Modifier">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('credits.destroy', $credit) }}" 
                  method="POST" 
                  class="d-inline"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce crédit ?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn btn-danger " title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-4 text-muted">
        @if(request('search'))
            <i class="fas fa-search me-2"></i>Aucun résultat trouvé pour "{{ request('search') }}"
        @else
            <i class="fas fa-inbox me-2"></i>Aucun crédit enregistré
        @endif
    </td>
</tr>
@endforelse