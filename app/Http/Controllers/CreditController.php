<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Sortie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ✅ فلترة حسب المستخدم الحالي
        $query = Credit::with(['client', 'creator'])
            ->where('user_id', auth()->id());

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($clientQuery) use ($search) {
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

            $existingClient = Client::where('name', $validated['client_name'])->first();

            if ($existingClient) {
                $latestCredit = $existingClient->credits()->latest()->first();

                if ($latestCredit) {
                    return redirect()
                        ->route('credits.show', $latestCredit->id)
                        ->with('info', "Le client '{$validated['client_name']}' existe déjà. Voici son dernier crédit.");
                } else {
                    return redirect()
                        ->route('credits.index')
                        ->with('info', "Le client '{$validated['client_name']}' existe déjà mais n'a pas encore de crédit.");
                }
            }

            $client = Client::create([
                'name' => $validated['client_name'],
                'phone' => $validated['client_phone'] ?? null,
                'address' => $validated['client_address'] ?? null,
            ]);

            $paidAmount = $validated['paid_amount'] ?? 0;
            $remainingAmount = $validated['amount'] - $paidAmount;

            $credit = Credit::create([
                'client_id' => $client->id,
                'amount' => $validated['amount'],
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'reason' => $validated['reason'] ?? null,
                'status' => $remainingAmount <= 0 ? 'paid' : 'active',
                'created_by' => auth()->id(),
            ]);

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

            return redirect()
                ->route('credits.show', $credit->id)
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
        // التحقق من الملكية
        if ($credit->user_id !== auth()->id()) {
            abort(403);
        }

        // ✅ إحصائيات الكريديت
        $creditStats = [
            'total_credits' => Credit::where('user_id', auth()->id())->count(),
            'paid_credits' => Credit::where('user_id', auth()->id())
                ->where('status', 'paid')
                ->count(),
            'pending_credits' => Credit::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->count(),
            'total_remaining' => Credit::where('user_id', auth()->id())
                ->sum('remaining_amount'),
        ];

        // ✅ Top 5 منتجات مرتبطة بهذا الكريديت
        $topProducts = Sortie::where('user_id', auth()->id())
            ->where('credit_id', $credit->id)
            ->with('product')
            ->select('product_id', DB::raw('SUM(quantite) as total_quantity'), DB::raw('SUM(total_price) as total_value'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // ✅ إحصائيات شهرية لهذا الكريديت
        $monthlyStats = Payment::where('credit_id', $credit->id)
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year)),
                    'total' => $item->total
                ];
            });

        // ✅ كريديات أخرى للعميل
        $otherCredits = Credit::where('client_id', $credit->client_id)
            ->where('id', '!=', $credit->id)
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('credits.show', compact(
            'credit',
            'creditStats',
            'topProducts',
            'monthlyStats',
            'otherCredits'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Credit $credit)
    {
        $credit->load(['client', 'creator', 'payments.creator']);

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

            $clientId = $credit->client_id;
            $clientName = $credit->client->name;

            $credit->payments()->delete();

            Sortie::where('credit_id', $credit->id)->update(['credit_id' => null]);

            $credit->delete();

            $clientHasOtherCredits = Credit::where('client_id', $clientId)->exists();

            if (!$clientHasOtherCredits) {
                Client::where('id', $clientId)->delete();
                Log::info("Client supprimé automatiquement: {$clientName} (ID: {$clientId})");
            }

            DB::commit();

            return redirect()->route('credits.index')
                ->with('success', 'Crédit supprimé avec succès!');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Credit deletion error', [
                'error' => $e->getMessage(),
                'credit_id' => $credit->id
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:credits,id'
            ]);

            $ids = $validated['ids'];

            DB::beginTransaction();

            try {
                $clientIds = Credit::whereIn('id', $ids)
                    ->pluck('client_id')
                    ->unique()
                    ->toArray();

                Payment::whereIn('credit_id', $ids)->delete();

                Sortie::whereIn('credit_id', $ids)->update(['credit_id' => null]);

                $count = Credit::whereIn('id', $ids)->delete();

                $deletedClients = 0;
                foreach ($clientIds as $clientId) {
                    $hasCredits = Credit::where('client_id', $clientId)->exists();
                    if (!$hasCredits) {
                        Client::where('id', $clientId)->delete();
                        $deletedClients++;
                    }
                }

                Log::info('Bulk delete credits', [
                    'credits_deleted' => $count,
                    'clients_deleted' => $deletedClients,
                    'user_id' => auth()->id()
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'count' => $count,
                    'message' => "$count crédit(s) et $deletedClients client(s) supprimé(s)"
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Bulk delete error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
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
     * البحث في الكريديات (AJAX)
     */
    public function search(Request $request)
    {
        try {
            $search = $request->input('search', '');

            $credits = Credit::with('client')
                ->where('user_id', auth()->id()) // ✅ إضافة فلتر المستخدم
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('reason', 'like', "%{$search}%")
                            ->orWhereHas('client', function ($clientQuery) use ($search) {
                                $clientQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            });
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $html = view('credits.partials.credit-rows', ['credits' => $credits])->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $credits->total()
            ]);
        } catch (\Exception $e) {
            Log::error('Search error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche'
            ], 500);
        }
    }
    public function downloadTemplate()
    {
        $fileName = 'credits_template.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () {
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

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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

        $callback = function () use ($credits) {
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
    public function showImport()
    {
        return view('credits.import');
    }

    /**
     * Importer depuis CSV
     */
    /**
     * Importer depuis CSV - متطابق مع Export
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'Veuillez sélectionner un fichier',
            'file.mimes' => 'Le fichier doit être au format CSV',
            'file.max' => 'Le fichier ne doit pas dépasser 5 Mo'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();

            // Lire le fichier avec encoding UTF-8
            $content = file_get_contents($path);
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
            $lines = explode("\n", $content);

            $data = array_map(function ($line) {
                return str_getcsv($line);
            }, $lines);

            // Supprimer le header (première ligne)
            $header = array_shift($data);

            // التحقق من أن الـ header صحيح
            $expectedHeaders = ['ID', 'Client', 'Téléphone', 'Montant Total', 'Montant Payé', 'Montant Restant', 'Statut', 'Raison', 'Date de Création', 'Créé par'];

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            try {
                foreach ($data as $index => $row) {
                    $lineNumber = $index + 2;

                    // Ignorer les lignes vides
                    if (empty(array_filter($row))) continue;

                    try {
                        // ✅ Extraire les données selon ترتيب Export
                        // [0] = ID (on ignore car auto-increment)
                        $clientName = trim($row[1] ?? '');           // Client
                        $phone = trim($row[2] ?? '');                // Téléphone
                        $montantTotal = (float) str_replace(',', '.', trim($row[3] ?? '0'));  // Montant Total
                        $montantPaye = (float) str_replace(',', '.', trim($row[4] ?? '0'));   // Montant Payé
                        // [5] = Montant Restant (calculé automatiquement)
                        $status = trim($row[6] ?? 'active');         // Statut
                        $raison = trim($row[7] ?? '');               // Raison
                        // [8] = Date (on ignore, sera créé automatiquement)
                        // [9] = Créé par (on ignore, sera auth()->id())

                        // ✅ Validation
                        if (empty($clientName)) {
                            $errors[] = "Ligne {$lineNumber}: Nom du client requis";
                            continue;
                        }

                        if ($montantTotal <= 0) {
                            $errors[] = "Ligne {$lineNumber}: Le montant doit être supérieur à 0";
                            continue;
                        }

                        if ($montantPaye > $montantTotal) {
                            $errors[] = "Ligne {$lineNumber}: Le montant payé ne peut pas dépasser le montant total";
                            continue;
                        }

                        // Validation du statut
                        if (!in_array($status, ['active', 'paid', 'cancelled'])) {
                            $status = 'active';
                        }

                        // ✅ Nettoyer le téléphone
                        $phone = $phone === 'N/A' ? null : $phone;

                        // ✅ Créer ou récupérer le client
                        $client = Client::firstOrCreate(
                            [
                                'phone' => $phone,
                                'user_id' => auth()->id()
                            ],
                            [
                                'name' => $clientName,
                            ]
                        );

                        // Si le client existe avec un téléphone différent, on cherche par nom
                        if (!$phone && !$client->wasRecentlyCreated) {
                            $client = Client::firstOrCreate(
                                [
                                    'name' => $clientName,
                                    'user_id' => auth()->id()
                                ],
                                [
                                    'phone' => $phone
                                ]
                            );
                        }

                        // ✅ Calculer le montant restant
                        $montantRestant = $montantTotal - $montantPaye;

                        // ✅ Créer le crédit
                        $credit = Credit::create([
                            'client_id' => $client->id,
                            'user_id' => auth()->id(),
                            'created_by' => auth()->id(),
                            'amount' => $montantTotal,
                            'paid_amount' => $montantPaye,
                            'remaining_amount' => $montantRestant,
                            'reason' => $raison ?: null,
                            'status' => $montantRestant <= 0 ? 'paid' : $status,
                        ]);

                        // ✅ Si un paiement initial existe, l'enregistrer
                        if ($montantPaye > 0) {
                            Payment::create([
                                'credit_id' => $credit->id,
                                'amount' => $montantPaye,
                                'payment_date' => now(),
                                'notes' => 'Paiement initial (import CSV)',
                                'created_by' => auth()->id(),
                            ]);
                        }

                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Ligne {$lineNumber}: {$e->getMessage()}";
                        Log::error("Import error line {$lineNumber}", [
                            'error' => $e->getMessage(),
                            'row' => $row
                        ]);
                    }
                }

                DB::commit();

                // ✅ Message de succès
                $message = "✅ {$imported} crédit(s) importé(s) avec succès";

                if (!empty($errors)) {
                    $errorCount = count($errors);
                    $message .= " | ⚠️ {$errorCount} erreur(s): " . implode(', ', array_slice($errors, 0, 3));
                    if ($errorCount > 3) {
                        $message .= " et " . ($errorCount - 3) . " autres erreurs";
                    }
                }

                return redirect()->route('credits.index')->with('success', $message);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Import error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }
}
