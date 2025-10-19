<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SortieController;
use Illuminate\Support\Facades\Route;

// الصفحة الرئيسية والمصادقة
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route إضافي للتتوافق
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Redirection pour les utilisateurs déjà connectés
Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// جميع المسارات المحمية بتسجيل الدخول
Route::middleware(['auth'])->group(function () {
    // المسارات الموحدة
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // المنتجات
    Route::resource('products', ProductController::class);

    // حركات المخزون
    Route::resource('sorties', SortieController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/sortie-stock', [SortieController::class, 'create'])->name('sorties.create');
    Route::post('/sortie-stock', [SortieController::class, 'store'])->name('sorties.store');

    // التنبيهات
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/get-alert-count', [AlertController::class, 'getAlertCount'])->name('alerts.count');

    // Gestion complète des crédits (CRUD)
    Route::resource('credits', CreditController::class)->names([
        'index' => 'credits.index',
        'create' => 'credits.create',
        'store' => 'credits.store',
        'show' => 'credits.show',
        'edit' => 'credits.edit',
        'update' => 'credits.update',
        'destroy' => 'credits.destroy',
    ]);

    // Route pour ajouter un paiement
    Route::post('/credits/{credit}/add-payment', [CreditController::class, 'addPayment'])->name('credits.add-payment');

    // Recherche et export
    Route::get('/credits-search', [CreditController::class, 'search'])->name('credits.search');
    Route::get('/credits-export', [CreditController::class, 'export'])->name('credits.export');

    // Routes API pour stats
    Route::prefix('api')->group(function () {
        Route::get('/stats/monthly', [DashboardController::class, 'getMonthlyStats'])->name('api.stats.monthly');
        Route::get('/stats/overview', [DashboardController::class, 'getOverviewStats'])->name('api.stats.overview');
        Route::get('/stats/stock', [DashboardController::class, 'getStockStats'])->name('api.stats.stock');
        Route::get('/stats/recent-movements', [DashboardController::class, 'getRecentStockMovements'])->name('api.stats.recent-movements');
        Route::get('/stats/alert-products', [DashboardController::class, 'getAlertProducts'])->name('api.stats.alert-products');
    });
});

// Route de fallback pour les 404
Route::fallback(function () {
    if (auth()->check()) { 
        return redirect()->route('dashboard')->with('error', 'Page non trouvée');
    }
    return redirect()->route('login.form')->with('error', 'Page non trouvée');
});