<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Credit::with(['client', 'creator']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($clientQuery) use ($search) {
                    $clientQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('phone', 'LIKE', "%{$search}%");
                })
                ->orWhere('reason', 'LIKE', "%{$search}%");
            });
        }

        $credits = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('credits.index', compact('credits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('credits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'paid_amount' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Créer ou trouver le client
            $client = Client::firstOrCreate(
                ['phone' => $validated['client_phone']],
                [
                    'name' => $validated['client_name'],
                    'address' => $validated['client_address'] ?? null,
                ]
            );

            // Si le client existe déjà mais le nom est différent, le mettre à jour
            if ($client->name !== $validated['client_name']) {
                $client->update(['name' => $validated['client_name']]);
            }

            // Calculer le montant restant
            $paidAmount = $validated['paid_amount'] ?? 0;
            $remainingAmount = $validated['amount'] - $paidAmount;

            // Créer le crédit
            $credit = Credit::create([
                'client_id' => $client->id,
                'amount' => $validated['amount'],
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'reason' => $validated['reason'] ?? null,
                'status' => $remainingAmount <= 0 ? 'paid' : 'active',
                'created_by' => auth()->id(),
            ]);

            // Si un paiement initial a été fait, l'enregistrer
            if ($paidAmount > 0) {
                Payment::create([
                    'credit_id' => $credit->id,
                    'amount' => $paidAmount,
                    'payment_date' => now(),
                    'notes' => 'Paiement initial',
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('credits.index')
                ->with('success', 'Crédit créé avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du crédit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Credit $credit)
    {
        $credit->load(['client', 'creator', 'payments.creator']);
        return view('credits.show', compact('credit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Credit $credit)
    {
        $credit->load(['client', 'creator']);
        return view('credits.edit', compact('credit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Credit $credit)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'status' => 'required|in:active,paid,cancelled',
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Mettre à jour le client
            $client = $credit->client;
            $client->update([
                'name' => $validated['client_name'],
                'phone' => $validated['client_phone'],
                'address' => $validated['client_address'] ?? null,
            ]);

            // Mettre à jour le crédit
            $credit->update([
                'amount' => $validated['amount'],
                'status' => $validated['status'],
                'reason' => $validated['reason'] ?? null,
            ]);

            // Si le statut est "payé", mettre à jour les montants
            if ($validated['status'] === 'paid') {
                $credit->update([
                    'paid_amount' => $validated['amount'],
                    'remaining_amount' => 0,
                ]);
            }

            DB::commit();

            return redirect()->route('credits.index')
                ->with('success', 'Crédit mis à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du crédit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Credit $credit)
    {
        try {
            DB::beginTransaction();

            // Supprimer d'abord les paiements associés
            $credit->payments()->delete();

            // Puis supprimer le crédit
            $credit->delete();

            DB::commit();

            return redirect()->route('credits.index')
                ->with('success', 'Crédit supprimé avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du crédit: ' . $e->getMessage());
        }
    }

    /**
     * Recherche AJAX pour les crédits
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');

        $credits = Credit::with(['client', 'creator'])
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->whereHas('client', function($clientQuery) use ($search) {
                        $clientQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('phone', 'LIKE', "%{$search}%");
                    })
                    ->orWhere('reason', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('credits.partials.credit-rows', compact('credits'))->render();

        return response()->json([
            'html' => $html,
            'count' => $credits->count()
        ]);
    }

    /**
     * Ajouter un paiement à un crédit
     */
    public function addPayment(Request $request, Credit $credit)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $credit->remaining_amount,
            'payment_notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Mettre à jour les montants du crédit
            $newPaidAmount = $credit->paid_amount + $request->payment_amount;
            $newRemainingAmount = $credit->amount - $newPaidAmount;

            $credit->update([
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => $newRemainingAmount,
                'status' => $newRemainingAmount <= 0 ? 'paid' : 'active'
            ]);

            // Enregistrer le paiement
            Payment::create([
                'credit_id' => $credit->id,
                'amount' => $request->payment_amount,
                'payment_date' => now(),
                'notes' => $request->payment_notes,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Paiement ajouté avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Exporter les crédits en CSV
     */
    public function export(Request $request)
    {
        $credits = Credit::with(['client', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = 'credits_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($credits) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Client',
                'Téléphone',
                'Montant Total',
                'Montant Payé',
                'Montant Restant',
                'Statut',
                'Raison',
                'Date de Création',
                'Créé par'
            ]);

            // Données
            foreach ($credits as $credit) {
                fputcsv($file, [
                    $credit->id,
                    $credit->client->name,
                    $credit->client->phone ?? 'N/A',
                    $credit->amount,
                    $credit->paid_amount,
                    $credit->remaining_amount,
                    $credit->status,
                    $credit->reason ?? 'N/A',
                    $credit->created_at->format('d/m/Y H:i'),
                    $credit->creator->name
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}