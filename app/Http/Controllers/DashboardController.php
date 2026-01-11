<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sortie;
use App\Models\Credit;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DateSql;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        // ===== إحصائيات المنتجات =====
        $totalProducts = Product::where('user_id', $userId)->count();
        $lowStockProducts = Product::where('user_id', $userId)
            ->whereColumn('quantite', '<=', 'seuil_alerte')
            ->where('quantite', '>', 0)
            ->count();
        $outOfStockProducts = Product::where('user_id', $userId)
            ->where('quantite', 0)
            ->count();
        $goodStockProducts = $totalProducts - $lowStockProducts - $outOfStockProducts;
        $totalQuantity = Product::where('user_id', $userId)->sum('quantite');

        // ===== إحصائيات الكريديت =====
        $totalCredits = Credit::where('user_id', $userId)->count();
        $totalAmount = Credit::where('user_id', $userId)->sum('amount');
        $activeCredits = Credit::where('user_id', $userId)
            ->where('status', 'active')
            ->count();
        $activeAmount = Credit::where('user_id', $userId)
            ->where('status', 'active')
            ->sum('amount');
        $totalRemaining = Credit::where('user_id', $userId)->sum('remaining_amount');

        // ===== إحصائيات الفئات =====
        $totalCategories = Category::where('user_id', $userId)
            ->where('actif', true)
            ->count();

        $categoriesData = Category::where('user_id', $userId)
            ->where('actif', true)
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        // ===== ✅ أكثر المنتجات مبيعاً هذا الشهر =====
        $topProductsThisMonth = Sortie::where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->select('product_id', DB::raw('SUM(quantite) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->limit(5)
            ->get();

        // ===== ✅ إجمالي المبيعات هذا الشهر =====
        $totalSalesThisMonth = Sortie::where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('quantite');

        // ✅ البيانات الشهرية للكريديت - متوافق مع PostgreSQL
        $query = Credit::where('user_id', $userId);
        DateSql::whereYear($query, 'created_at', date('Y'));

        $monthlyData = $query
            ->select(
                DateSql::month('created_at','month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total'),
                DB::raw('SUM(paid_amount) as paid'),
                DB::raw('SUM(remaining_amount) as remaining')
            )
            ->groupBy(DateSql::month('created_at'))
            ->orderBy(DateSql::month('created_at'))
            ->get()
            ->map(function ($item) {
                $monthNames = [
                    1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
                    5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
                    9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
                ];
                return [
                    'month' => $monthNames[$item->month],
                    'count' => $item->count,
                    'total' => $item->total,
                    'paid' => $item->paid,
                    'remaining' => $item->remaining
                ];
            });

        // ===== أفضل العملاء - ✅ محدث للتوافق مع PostgreSQL =====
        $topClients = Client::where('user_id', $userId)
            ->withCount('credits')
            ->withSum('credits', 'amount')
            ->whereHas('credits')
            ->orderBy('credits_sum_amount', 'desc')
            ->limit(5)
            ->get();

        // ===== المنتجات في حالة تنبيه =====
        $alertProducts = Product::where('user_id', $userId)
            ->whereColumn('quantite', '<=', 'seuil_alerte')
            ->orderBy('quantite')
            ->limit(5)
            ->get();

        // ===== آخر الحركات =====
        $recentMovements = Sortie::where('user_id', $userId)
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ===== الكريديتات الأخيرة =====
        $recentCredits = Credit::where('user_id', $userId)
            ->with('client')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ===== أفضل العملاء هذا الشهر =====
        $topClientsThisMonth = Client::where('user_id', $userId)
            ->withCount(['credits' => function ($query) {
                $query->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
            }])
            ->withSum(['credits' => function ($query) {
                $query->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
            }], 'amount')
            ->orderBy('credits_sum_amount', 'desc')
            ->limit(5)
            ->get();

        // Total number of clients who had credits this month
        $totalClientsThisMonth = Client::where('user_id', $userId)
            ->whereHas('credits', function ($q) {
                $q->whereYear('created_at', now()->year)
                  ->whereMonth('created_at', now()->month);
            })->count();

        // ===== الكريديتات المقسمة =====
        $query = Credit::where('user_id', $userId)->with(['client', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($clientQuery) use ($search) {
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
            'totalProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'goodStockProducts',
            'totalQuantity',
            'alertProducts',
            'recentMovements',
            'totalCredits',
            'totalAmount',
            'activeCredits',
            'activeAmount',
            'totalRemaining',
            'recentCredits',
            'monthlyData',
            'topClients',
            'topClientsThisMonth',
            'totalClientsThisMonth',
            'paginatedCredits',
            'totalCategories',
            'categoriesData',
            'topProductsThisMonth',
            'totalSalesThisMonth'
        ));
    }

    public function getOverviewStats()
    {
        $userId = Auth::id();
        $totalCredits = Credit::where('user_id', $userId)->count();
        $totalAmount = Credit::where('user_id', $userId)->sum('amount');
        $activeCredits = Credit::where('user_id', $userId)->where('status', 'active')->count();
        $totalRemaining = Credit::where('user_id', $userId)->sum('remaining_amount');
        $paidAmount = $totalAmount - $totalRemaining;

        return response()->json([
            'total_credits' => $totalCredits,
            'total_amount' => $totalAmount,
            'active_credits' => $activeCredits,
            'total_remaining' => $totalRemaining,
            'paid_amount' => $paidAmount,
            'payment_rate' => $totalAmount > 0 ? round(($paidAmount / $totalAmount) * 100, 2) : 0
        ]);
    }

    public function getMonthlyStats()
    {
        $userId = Auth::id();

        $query = Credit::where('user_id', $userId);
        DateSql::whereYear($query, 'created_at', date('Y'));

        $monthlyStats = $query
            ->select(
                DateSql::year('created_at','year'),
                DateSql::month('created_at','month'),
                DB::raw('COUNT(*) as credits_count'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('SUM(paid_amount) as total_paid'),
                DB::raw('SUM(remaining_amount) as total_remaining')
            )
            ->groupBy(DateSql::year('created_at'), DateSql::month('created_at'))
            ->orderByDesc(DateSql::year('created_at'))
            ->orderByDesc(DateSql::month('created_at'))
            ->get()
            ->map(function ($item) {
                $monthNames = [
                    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                ];
                return [
                    'month' => $monthNames[$item->month] . ' ' . $item->year,
                    'credits_count' => $item->credits_count,
                    'total_amount' => $item->total_amount,
                    'total_paid' => $item->total_paid,
                    'total_remaining' => $item->total_remaining
                ];
            });

        return response()->json($monthlyStats);
    }

    public function addPayment(Request $request, Credit $credit)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $credit->remaining_amount,
            'payment_notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $newPaidAmount = $credit->paid_amount + $request->payment_amount;
            $newRemainingAmount = $credit->amount - $newPaidAmount;

            $credit->update([
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => $newRemainingAmount,
                'status' => $newRemainingAmount <= 0 ? 'paid' : 'active'
            ]);

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

    public function getStockStats()
    {
        $userId = Auth::id();
        $totalProducts = Product::where('user_id', $userId)->count();
        $lowStockProducts = Product::where('user_id', $userId)
            ->where('quantite', '<=', DB::raw('seuil_alerte'))
            ->where('quantite', '>', 0)
            ->count();
        $outOfStockProducts = Product::where('user_id', $userId)
            ->where('quantite', 0)
            ->count();
        $goodStockProducts = $totalProducts - $lowStockProducts - $outOfStockProducts;

        return response()->json([
            'total_products' => $totalProducts,
            'low_stock' => $lowStockProducts,
            'out_of_stock' => $outOfStockProducts,
            'good_stock' => $goodStockProducts
        ]);
    }

    public function getRecentStockMovements()
    {
        $userId = Auth::id();
        $recentMovements = Sortie::where('user_id', $userId)
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($movement) {
                return [
                    'id' => $movement->id,
                    'product_name' => $movement->product->marque . ' (' . $movement->product->taille . ')',
                    'quantity' => $movement->quantite,
                    'client_name' => $movement->nom_client,
                    'date' => $movement->created_at->format('d/m/Y H:i'),
                    'user' => $movement->user->name ?? 'N/A'
                ];
            });

        return response()->json($recentMovements);
    }

    public function getAlertProducts()
    {
        $userId = Auth::id();
        $alertProducts = Product::where('user_id', $userId)
            ->where('quantite', '<=', DB::raw('seuil_alerte'))
            ->orderBy('quantite')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'marque' => $product->marque,
                    'taille' => $product->taille,
                    'quantite' => $product->quantite,
                    'seuil_alerte' => $product->seuil_alerte,
                    'price' => $product->price,
                    'status' => $product->quantite == 0 ? 'out_of_stock' : ($product->quantite <= $product->seuil_alerte ? 'low_stock' : 'good_stock')
                ];
            });

        return response()->json($alertProducts);
    }
}