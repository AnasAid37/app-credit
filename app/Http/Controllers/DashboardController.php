<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        // Statistiques générales
        $totalCredits = Credit::count();
        $totalAmount = Credit::sum('amount');
        $activeCredits = Credit::where('status', 'active')->count();
        $activeAmount = Credit::where('status', 'active')->sum('amount');

        // Crédits récents (7 derniers jours)
        $recentCredits = Credit::with('client')
                              ->where('created_at', '>=', now()->subDays(7))
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        // Données mensuelles pour le graphique
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

        return view('dashboard', compact(
            'totalCredits', 'totalAmount', 'activeCredits', 'activeAmount',
            'recentCredits', 'monthlyData', 'topClients'
        ));
    }
}