<?php
// app/Http/Controllers/CreditController.php - Version complète

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Client;
use App\Http\Requests\StoreCreditRequest;
use App\Http\Requests\UpdateCreditRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->search($request);
        }

        $credits = Credit::with(['client', 'creator'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        // Statistiques pour le dashboard
        $stats = $this->getStats();

        return view('credits.index', compact('credits', 'stats'));
    }

    public function create()
    {
        return view('credits.create');
    }

    public function store(StoreCreditRequest $request)
    {
        try {
            DB::beginTransaction();

            // Créer ou récupérer le client
            $client = Client::firstOrCreate([
                'name' => $request->client_name
            ], [
                'phone' => $request->client_phone,
                'address' => $request->client_address
            ]);

            // Si le client existe déjà, mettre à jour ses informations
            if ($request->client_phone || $request->client_address) {
                $client->update([
                    'phone' => $request->client_phone ?: $client->phone,
                    'address' => $request->client_address ?: $client->address
                ]);
            }

            // Créer le crédit
            Credit::create([
                'client_id' => $client->id,
                'amount' => $request->amount,
                'reason' => $request->reason,
                'created_by' => auth()->id(),
                'status' => 'active'
            ]);

            DB::commit();

            return redirect()->route('credits.index')
                            ->with('success', 'Crédit ajouté avec succès');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                            ->with('error', 'Erreur lors de l\'ajout du crédit')
                            ->withInput();
        }
    }

    public function show(Credit $credit)
    {
        $credit->load(['client', 'creator', 'payments']);
        return view('credits.show', compact('credit'));
    }

    public function edit(Credit $credit)
    {
        $credit->load('client');
        return view('credits.edit', compact('credit'));
    }

    public function update(UpdateCreditRequest $request, Credit $credit)
    {
        try {
            DB::beginTransaction();

            // Mettre à jour le client
            $credit->client->update([
                'name' => $request->client_name,
                'phone' => $request->client_phone,
                'address' => $request->client_address
            ]);

            // Mettre à jour le crédit
            $credit->update([
                'amount' => $request->amount,
                'reason' => $request->reason,
                'status' => $request->status
            ]);

            DB::commit();

            return redirect()->route('credits.index')
                            ->with('success', 'Crédit modifié avec succès');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                            ->with('error', 'Erreur lors de la modification')
                            ->withInput();
        }
    }

    public function destroy(Credit $credit)
    {
        try {
            $clientName = $credit->client->name;
            $credit->delete();
            
            return redirect()->route('credits.index')
                            ->with('success', "Crédit de {$clientName} supprimé avec succès");
        } catch (\Exception $e) {
            return redirect()->route('credits.index')
                            ->with('error', 'Erreur lors de la suppression');
        }
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('search', '');
        
        $credits = Credit::with(['client', 'creator'])
            ->when($query, function($q) use ($query) {
                return $q->whereHas('client', function($clientQuery) use ($query) {
                    $clientQuery->where('name', 'LIKE', "%{$query}%")
                               ->orWhere('phone', 'LIKE', "%{$query}%");
                })
                ->orWhere('reason', 'LIKE', "%{$query}%")
                ->orWhere('amount', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('credits.partials.credit-rows', compact('credits'))->render();

        return response()->json([
            'html' => $html,
            'count' => $credits->count()
        ]);
    }

    public function export(Request $request)
    {
        $query = $request->get('search', '');
        
        $credits = Credit::with(['client', 'creator'])
            ->when($query, function($q) use ($query) {
                return $q->whereHas('client', function($clientQuery) use ($query) {
                    $clientQuery->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('reason', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'credits_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($credits) {
            $file = fopen('php://output', 'w');
            
            // Headers CSV
            fputcsv($file, [
                'ID', 'Client', 'Téléphone', 'Montant (DH)', 
                'Raison', 'Statut', 'Date de création', 'Créé par'
            ]);
            
            foreach ($credits as $credit) {
                fputcsv($file, [
                    $credit->id,
                    $credit->client->name,
                    $credit->client->phone ?: 'N/A',
                    number_format($credit->amount, 2),
                    $credit->reason ?: 'N/A',
                    ucfirst($credit->status),
                    $credit->created_at->format('d/m/Y H:i'),
                    $credit->creator->name
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getStats()
    {
        return [
            'total_credits' => Credit::count(),
            'total_amount' => Credit::sum('amount'),
            'active_credits' => Credit::where('status', 'active')->count(),
            'active_amount' => Credit::where('status', 'active')->sum('amount'),
            'paid_credits' => Credit::where('status', 'paid')->count(),
            'paid_amount' => Credit::where('status', 'paid')->sum('amount'),
            'cancelled_credits' => Credit::where('status', 'cancelled')->count(),
            'recent_credits' => Credit::where('created_at', '>=', now()->subDays(7))->count(),
            'top_clients' => Client::withCount('credits')
                                  ->having('credits_count', '>', 1)
                                  ->orderBy('credits_count', 'desc')
                                  ->limit(5)
                                  ->get()
        ];
    }

    public function dashboard()
    {
        $stats = $this->getStats();
        
        // Données pour les graphiques
        $monthlyData = Credit::selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(amount) as total')
                           ->whereYear('created_at', date('Y'))
                           ->groupBy('month')
                           ->orderBy('month')
                           ->get();

        $statusData = Credit::selectRaw('status, COUNT(*) as count, SUM(amount) as total')
                          ->groupBy('status')
                          ->get();

        return view('credits.dashboard', compact('stats', 'monthlyData', 'statusData'));
    }
}