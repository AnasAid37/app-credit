{{-- resources/views/credits/partials/credit-rows.blade.php --}}

@forelse($credits as $credit)
<tr>
    <!-- Client -->
    <td>
        <div class="d-flex align-items-center">
            <div class="client-avatar me-2">
                @php
                $clientName = $credit->client_name ?? ($credit->client->name ?? 'N');
                @endphp
                {{ strtoupper(substr($clientName, 0, 1)) }}
            </div>
            <div>
                <div class="fw-bold">{{ $clientName }}</div>
                @php
                $clientAddress = $credit->client_address ?? ($credit->client->address ?? null);
                @endphp
                @if($clientAddress)
                <small class="text-muted d-block">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ Str::limit($clientAddress, 30) }}
                </small>
                @endif
            </div>
        </div>
    </td>

    <!-- Téléphone -->
    <td>
        @php
        $clientPhone = $credit->client_phone ?? ($credit->client->phone ?? null);
        @endphp
        @if($clientPhone)
        <a href="tel:{{ $clientPhone }}" class="phone-link">
            <i class="fas fa-phone-alt me-1"></i>
            {{ $clientPhone }}
        </a>
        @else
        <span class="text-muted">-</span>
        @endif
    </td>

    <!-- Montant total -->
    <td>
        <span class="fw-bold text-primary">
            {{ number_format($credit->amount, 2) }} DH
        </span>
    </td>

    <!-- Montant payé -->
    <td>
        <span class="fw-bold text-success">
            {{ number_format($credit->paid_amount, 2) }} DH
        </span>
    </td>

    <!-- Montant restant -->
    <td>
        @php
        $remaining = $credit->remaining_amount ?? ($credit->amount - $credit->paid_amount);
        @endphp
        <span class="fw-bold {{ $remaining > 0 ? 'text-warning' : 'text-success' }}">
            {{ number_format($remaining, 2) }} DH
        </span>
    </td>

    <!-- Raison -->
    <td>
        @if($credit->reason)
        <span class="text-muted small d-inline-flex align-items-center" title="{{ $credit->reason }}">
            <i class="fas fa-comment-dots me-1"></i>
            {{ Str::limit($credit->reason, 30) }}
        </span>
        @else
        <span class="text-muted">-</span>
        @endif
    </td>

    <!-- Statut -->
    <td>
        @php
        $remaining = $credit->remaining_amount ?? ($credit->amount - $credit->paid_amount);

        // Déterminer le statut basé sur le champ status ou calculé
        if (isset($credit->status)) {
        // Si le champ status existe
        switch($credit->status) {
        case 'paid':
        $statusInfo = ['text' => 'Payé', 'class' => 'badge-success', 'icon' => 'fa-check-circle'];
        break;
        case 'active':
        if ($remaining <= 0) {
            $statusInfo=['text'=> 'Payé', 'class' => 'badge-success', 'icon' => 'fa-check-circle'];
            } elseif ($credit->paid_amount > 0) {
            $statusInfo = ['text' => 'Partiel', 'class' => 'badge-warning', 'icon' => 'fa-clock'];
            } else {
            $statusInfo = ['text' => 'En attente', 'class' => 'badge-danger', 'icon' => 'fa-exclamation-circle'];
            }
            break;
            case 'cancelled':
            $statusInfo = ['text' => 'Annulé', 'class' => 'badge-danger', 'icon' => 'fa-times-circle'];
            break;
            case 'pending':
            $statusInfo = ['text' => 'En attente', 'class' => 'badge-warning', 'icon' => 'fa-clock'];
            break;
            default:
            $statusInfo = ['text' => 'Actif', 'class' => 'badge-primary', 'icon' => 'fa-play-circle'];
            }
            } else {
            // Calcul automatique du statut
            if ($remaining <= 0) {
                $statusInfo=['text'=> 'Payé', 'class' => 'badge-success', 'icon' => 'fa-check-circle'];
                } elseif ($credit->paid_amount > 0) {
                $statusInfo = ['text' => 'Partiel', 'class' => 'badge-warning', 'icon' => 'fa-clock'];
                } else {
                $statusInfo = ['text' => 'En attente', 'class' => 'badge-danger', 'icon' => 'fa-exclamation-circle'];
                }
                }
                @endphp
                <span class="badge-modern {{ $statusInfo['class'] }}">
                    <i class="fas {{ $statusInfo['icon'] }}"></i>
                    {{ $statusInfo['text'] }}
                </span>
    </td>

    <!-- Date -->
    <td>
        <div class="text-muted small">
            <div>
                <i class="fas fa-calendar me-1"></i>
                {{ $credit->created_at->format('d/m/Y') }}
            </div>
            <div class="mt-1">
                <i class="fas fa-clock me-1"></i>
                {{ $credit->created_at->format('H:i') }}
            </div>
        </div>
    </td>

    <!-- Actions -->
    <td class="text-center">
        <div class="action-btn-group justify-content-center">
            <a href="{{ route('credits.show', $credit->id) }}"
                class="action-btn action-btn-view"
                title="Voir les détails">
                <i class="fas fa-eye"></i>
            </a>
            
            <a href="{{ route('credits.edit', $credit->id) }}"
                class="action-btn action-btn-edit"
                title="Modifier">
                <i class="fas fa-edit"></i>
            </a>
            
            @php
            $canDelete = true;
            if (isset($credit->status)) {
            $canDelete = $credit->status !== 'paid';
            } else {
            $canDelete = $remaining > 0;
            }
            @endphp
            
            <button type="button"
                class="action-btn action-btn-delete"
                onclick="confirmDelete({{ $credit->id }}, '{{ addslashes($clientName) }}')"
                title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
            

        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center py-5">
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h5>Aucun résultat trouvé</h5>
            <p class="text-muted">Aucun crédit ne correspond à votre recherche.</p>
        </div>
    </td>
</tr>
@endforelse

<style>
    /* Client Avatar */
    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 0.75rem;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* Phone Link */
    .phone-link {
        color: #10b981;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .phone-link:hover {
        color: #059669;
        transform: translateX(2px);
    }

    /* Badge Modern Styles */
    .badge-modern {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .badge-primary {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    /* Action Buttons */
    .action-btn-group {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .action-btn-view {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .action-btn-view:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
    }

    .action-btn-edit {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .action-btn-edit:hover {
        background: #f59e0b;
        color: white;
        transform: translateY(-2px);
    }

    .action-btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .action-btn-delete:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .action-btn-group {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
        }
    }
</style>

<script>
    // Fonction de confirmation de suppression moderne
    function confirmDelete(id, clientName) {
        // Créer une modal personnalisée
        const modal = document.createElement('div');
        modal.className = 'custom-confirm-modal';
        modal.innerHTML = `
        <div class="custom-confirm-overlay" onclick="closeConfirmModal()"></div>
        <div class="custom-confirm-content">
            <div class="custom-confirm-header">
                <div class="stat-icon danger mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="fw-bold mb-2">Confirmer la suppression</h5>
            </div>
            <div class="custom-confirm-body">
                <p class="text-center mb-3">Êtes-vous sûr de vouloir supprimer ce crédit ?</p>
                <div class="alert alert-warning d-flex align-items-center mb-3">
                    <i class="fas fa-user me-2"></i>
                    <div>
                        <strong>Client:</strong> ${clientName}
                    </div>
                </div>
                <p class="text-muted small mb-0 text-center">
                    <i class="fas fa-info-circle me-1"></i>
                    Cette action est irréversible.
                </p>
            </div>
            <div class="custom-confirm-footer">
                <button class="btn btn-modern-outline" onclick="closeConfirmModal()">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <button class="btn btn-modern-primary" style="background: #ef4444;" onclick="executeDelete(${id})">
                    <i class="fas fa-trash me-1"></i> Supprimer
                </button>
            </div>
        </div>
    `;

        document.body.appendChild(modal);

        // Animation d'entrée
        setTimeout(() => {
            modal.querySelector('.custom-confirm-overlay').style.opacity = '1';
            modal.querySelector('.custom-confirm-content').style.transform = 'scale(1)';
            modal.querySelector('.custom-confirm-content').style.opacity = '1';
        }, 10);
    }

    function closeConfirmModal() {
        const modal = document.querySelector('.custom-confirm-modal');
        if (modal) {
            modal.querySelector('.custom-confirm-overlay').style.opacity = '0';
            modal.querySelector('.custom-confirm-content').style.transform = 'scale(0.9)';
            modal.querySelector('.custom-confirm-content').style.opacity = '0';
            setTimeout(() => modal.remove(), 300);
        }
    }

    function executeDelete(id) {
        // Créer et soumettre le formulaire
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/credits/${id}`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    // Style pour la modal personnalisée (يتم إضافة هذا مرة واحدة فقط)
    if (!document.getElementById('custom-confirm-styles')) {
        const style = document.createElement('style');
        style.id = 'custom-confirm-styles';
        style.textContent = `
        .custom-confirm-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .custom-confirm-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }
        
        .custom-confirm-content {
            position: relative;
            background: white;
            border-radius: 1rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .custom-confirm-header {
            padding: 2rem 2rem 1rem 2rem;
            text-align: center;
        }
        
        .custom-confirm-body {
            padding: 0 2rem 1rem 2rem;
        }
        
        .custom-confirm-footer {
            padding: 1rem 2rem 2rem 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .stat-icon {
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-icon.danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .btn-modern-outline {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
            border: 2px solid #e2e8f0;
            color: #64748b;
        }
        
        .btn-modern-outline:hover {
            border-color: #6366f1;
            color: #6366f1;
        }
        
        .btn-modern-primary {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            color: white;
        }
        
        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4);
        }
    `;
        document.head.appendChild(style);
    }
</script>