<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques (non authentifiées)
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Redirection pour les utilisateurs déjà connectés
Route::middleware('auth')->group(function () {
    // Redirection par défaut
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    });
});

// Routes protégées (admin uniquement)
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
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
    });
});

// Route de fallback pour les 404
Route::fallback(function () {
    if (auth()->check()) { 
        return redirect()->route('dashboard')->with('error', 'Page non trouvée');
    }
    return redirect()->route('login.form')->with('error', 'Page non trouvée');
});
