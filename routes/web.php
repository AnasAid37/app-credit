<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SortieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;

// ============= المصادقة =============
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// ============= صفحة الاشتراك =============
Route::middleware('auth')->group(function () {
    Route::get('/subscribe', [SubscribeController::class, 'show'])->name('subscribe.show');
});

// ============= صفحات Admin =============
Route::prefix('admin')->middleware(['auth', 'check.access:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/users/{user}/activate', [AdminController::class, 'activate'])->name('admin.activate');
    Route::post('/users/{user}/deactivate', [AdminController::class, 'deactivate'])->name('admin.deactivate');
    Route::post('/users/{user}/extend', [AdminController::class, 'extend'])->name('admin.extend');
});

// ============= التطبيق العادي (محمي بالاشتراك) =============
Route::middleware(['auth', 'check.access:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings');

    // المنتجات
    Route::resource('products', ProductController::class);

    // حركات المخزون
    Route::resource('sorties', SortieController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/sortie-stock', [SortieController::class, 'create'])->name('sorties.create');
    Route::post('/sortie-stock', [SortieController::class, 'store'])->name('sorties.store');

    // التنبيهات
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/get-alert-count', [AlertController::class, 'getAlertCount'])->name('alerts.count');

    // الكريديات
    Route::resource('credits', CreditController::class);
    Route::post('/credits/{credit}/add-payment', [CreditController::class, 'addPayment'])->name('credits.add-payment');
    Route::get('/credits-search', [CreditController::class, 'search'])->name('credits.search');
    Route::get('/credits-export', [CreditController::class, 'export'])->name('credits.export');

    // API Stats
    Route::prefix('api')->group(function () {
        Route::get('/stats/monthly', [DashboardController::class, 'getMonthlyStats'])->name('api.stats.monthly');
        Route::get('/stats/overview', [DashboardController::class, 'getOverviewStats'])->name('api.stats.overview');
        Route::get('/stats/stock', [DashboardController::class, 'getStockStats'])->name('api.stats.stock');
        Route::get('/stats/recent-movements', [DashboardController::class, 'getRecentStockMovements'])->name('api.stats.recent-movements');
        Route::get('/stats/alert-products', [DashboardController::class, 'getAlertProducts'])->name('api.stats.alert-products');
    });
});

// Fallback
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard')->with('error', 'Page non trouvée');
    }
    return redirect()->route('login.form')->with('error', 'Page non trouvée');
});