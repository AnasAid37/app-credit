@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>🔧 Gestion des utilisateurs</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            Retour à l'application
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Recherche simple --}}
    <form method="GET" class="mb-3">
        <input type="text" name="search" class="form-control" 
               placeholder="Rechercher par nom ou email..." 
               value="{{ request('search') }}">
    </form>

    {{-- Tableau des utilisateurs --}}
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Type</th>
                    <th>Expire le</th>
                    <th width="300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><strong>{{ $user->nom }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </td>
                    <td>
                        @if($user->subscription_type === 'lifetime')
                            <span class="badge bg-primary">À vie</span>
                        @elseif($user->subscription_type === 'monthly')
                            <span class="badge bg-info">Mensuel</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($user->subscription_expires_at)
                            {{ $user->subscription_expires_at->format('Y-m-d') }}
                            @php $days = now()->diffInDays($user->subscription_expires_at, false); @endphp
                            @if($days >= 0)
                                <small class="text-muted">({{ $days }} jours)</small>
                            @else
                                <small class="text-danger">(Expiré)</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        {{-- Activation --}}
                        @if(!$user->is_active)
                            <button class="btn btn-success btn-sm" 
                                    onclick="showActivateModal({{ $user->id }}, '{{ $user->nom }}')">
                                Activer
                            </button>
                        @else
                            {{-- Désactivation --}}
                            <form method="POST" action="{{ route('admin.deactivate', $user) }}" 
                                  style="display:inline" 
                                  onsubmit="return confirm('Désactiver {{ $user->nom }}?')">
                                @csrf
                                <button class="btn btn-warning btn-sm">Désactiver</button>
                            </form>

                            {{-- Prolongation (mensuel uniquement) --}}
                            @if($user->subscription_type === 'monthly')
                                <button class="btn btn-info btn-sm" 
                                        onclick="showExtendModal({{ $user->id }}, '{{ $user->nom }}')">
                                    Prolonger
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Aucun utilisateur trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>

{{-- Modal d'activation --}}
<div class="modal fade" id="activateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="activateForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Activer le compte : <span id="activateUserName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Type d'abonnement</label>
                        <select name="subscription_type" class="form-select" id="subType" required>
                            <option value="monthly">Mensuel</option>
                            <option value="lifetime">À vie</option>
                        </select>
                    </div>
                    <div class="mb-3" id="monthsField">
                        <label class="form-label">Nombre de mois</label>
                        <input type="number" name="months" class="form-control" value="1" min="1" max="12">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Activer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de prolongation --}}
<div class="modal fade" id="extendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="extendForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Prolonger l'abonnement : <span id="extendUserName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nombre de mois</label>
                    <input type="number" name="months" class="form-control" value="1" min="1" max="12" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info">Prolonger</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Afficher le modal d'activation
function showActivateModal(userId, userName) {
    document.getElementById('activateUserName').textContent = userName;
    document.getElementById('activateForm').action = `/admin/users/${userId}/activate`;
    new bootstrap.Modal(document.getElementById('activateModal')).show();
}

// Afficher le modal de prolongation
function showExtendModal(userId, userName) {
    document.getElementById('extendUserName').textContent = userName;
    document.getElementById('extendForm').action = `/admin/users/${userId}/extend`;
    new bootstrap.Modal(document.getElementById('extendModal')).show();
}

// Masquer le champ mois pour l'abonnement à vie
document.getElementById('subType')?.addEventListener('change', function() {
    document.getElementById('monthsField').style.display = 
        this.value === 'monthly' ? 'block' : 'none';
});
</script>
@endsection