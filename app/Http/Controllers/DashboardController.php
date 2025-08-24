<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Client;
use App\Models\Payment; // تأكد من وجود هذا الموديل
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        // إحصائيات عامة
        $totalCredits = Credit::count();
        $totalAmount = Credit::sum('amount');
        $activeCredits = Credit::where('status', 'active')->count();
        $activeAmount = Credit::where('status', 'active')->sum('amount');
        $totalRemaining = Credit::sum('remaining_amount');

        // Crédits récents (7 derniers jours)
        $recentCredits = Credit::with('client')
                              ->where('created_at', '>=', now()->subDays(7))
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        // Données mensuelles pour les graphiques
        $monthlyData = Credit::selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(amount) as total')
                           ->whereYear('created_at', date('Y'))
                           ->groupBy('month')
                           ->orderBy('month')
                           ->get()
                           ->map(function($item) {
                               $monthNames = [
                                   1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
                                   5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
                                   9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
                               ];
                               return [
                                   'month' => $monthNames[$item->month],
                                   'count' => $item->count,
                                   'total' => $item->total
                               ];
                           });

        // Top 5 des clients
        $topClients = Client::withCount('credits')
                           ->withSum('credits', 'amount')
                           ->having('credits_count', '>', 0)
                           ->orderBy('credits_sum_amount', 'desc')
                           ->limit(5)
                           ->get();

        // Crédits paginés avec recherche et filtrage
        $query = Credit::with(['client', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($clientQuery) use ($search) {
                    $clientQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('phone', 'LIKE', "%{$search}%");
                })
                ->orWhere('reason', 'LIKE', "%{$search}%")
                ->orWhereDate('created_at', $search);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $paginatedCredits = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('dashboard', compact(
            'totalCredits', 'totalAmount', 'activeCredits', 'activeAmount', 'totalRemaining',
            'recentCredits', 'monthlyData', 'topClients', 'paginatedCredits'
        ));
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

            // تحديث المدفوعات والكريدي
            $newPaidAmount = $credit->paid_amount + $request->payment_amount;
            $newRemainingAmount = $credit->amount - $newPaidAmount;

            $credit->update([
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => $newRemainingAmount,
                'status' => $newRemainingAmount <= 0 ? 'paid' : 'active'
            ]);

            // حفظ سجل الدفع
            Payment::create([
                'credit_id' => $credit->id,
                'amount' => $request->payment_amount,
                'payment_date' => now(),
                'notes' => $request->payment_notes,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Paiement ajouté avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout du paiement : ' . $e->getMessage());
        }
    }
}
