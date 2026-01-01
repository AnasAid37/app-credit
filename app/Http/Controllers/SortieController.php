<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sortie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;
use App\Models\Credit;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;


class SortieController extends Controller
{
    public function index(Request $request)
    {
        // ✅ فلترة حسب المستخدم الحالي
        $query = Sortie::with(['product', 'user', 'credit'])
            ->where('user_id', auth()->id());

        // تطبيق الفلاتر
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        if ($request->filled('nom_client')) {
            $query->where('nom_client', 'like', "%{$request->nom_client}%");
        }

        if ($request->filled('motif_sortie')) {
            $query->where('motif_sortie', 'like', "%{$request->motif_sortie}%");
        }

        // حساب الإحصائيات
        $stats = [
            'total_quantity' => $query->sum('quantite'),
            'total_amount' => $query->sum('total_price'),
            'total_count' => $query->count(),
        ];

        $sorties = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('sorties.index', compact('sorties', 'stats'));
    }

    public function create()
    {
        // ✅ فلترة المنتجات حسب المستخدم
        $products = Product::where('user_id', auth()->id())
            ->where('quantite', '>', 0)
            ->get();

        return view('sorties.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantite' => 'required|integer|min:1',
            'nom_client' => 'required|string|max:255',
            'motif_sortie' => 'required|string',
            'autre_motif' => 'nullable|string|max:500',
            'payment_mode' => 'required|in:cash,credit',
            'credit_paid_amount' => 'nullable|numeric|min:0',
            'credit_reason' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::where('id', $validated['product_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($product->quantite < $validated['quantite']) {
                return redirect()->back()
                    ->with('error', 'Stock insuffisant. Stock disponible: ' . $product->quantite)
                    ->withInput();
            }

            $totalPrice = $product->price * $validated['quantite'];

            $motifFinal = $validated['motif_sortie'] === 'Autre'
                ? ($validated['autre_motif'] ?? 'Autre')
                : $validated['motif_sortie'];

            // ✅ إنشاء Sortie
            $sortie = Sortie::create([
                'product_id' => $product->id,
                'quantite' => $validated['quantite'],
                'nom_client' => $validated['nom_client'],
                'motif_sortie' => $motifFinal,
                'total_price' => $totalPrice,
                'payment_mode' => $validated['payment_mode'],
                'user_id' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            $product->decrement('quantite', $validated['quantite']);

            $successMessage = "Sortie enregistrée avec succès";

            // ============================================
            // ✅ معالجة الدفع بالكريديت
            // ============================================
            if ($validated['payment_mode'] === 'credit') {
                $client = Client::firstOrCreate(
                    [
                        'name' => $validated['nom_client'],
                        'user_id' => auth()->id()
                    ],
                    [
                        'phone' => null,
                        'address' => null
                    ]
                );

                // البحث عن كريديت نشط للعميل
                $activeCredit = Credit::where('client_id', $client->id)
                    ->where('user_id', auth()->id())
                    ->where('status', '!=', 'paid')
                    ->latest()
                    ->first();

                $paidAmount = $validated['credit_paid_amount'] ?? 0;
                $newDebt = $totalPrice - $paidAmount; // المبلغ الجديد المتبقي

                if ($activeCredit) {
                    // ============================================
                    // ✅ تحديث الكريديت الموجود
                    // ============================================

                    $oldAmount = $activeCredit->amount;
                    $oldRemaining = $activeCredit->remaining_amount;

                    // إضافة المبلغ الجديد
                    $activeCredit->amount += $totalPrice;
                    $activeCredit->remaining_amount += $newDebt;

                    // إضافة السبب الجديد إلى السبب القديم
                    $newReason = $validated['credit_reason'] ?? "Sortie stock - {$motifFinal}";
                    $activeCredit->reason .= "\n➕ " . now()->format('d/m/Y H:i') . ": " . $newReason;

                    // تحديث paid_amount إذا تم دفع مبلغ
                    if ($paidAmount > 0) {
                        $activeCredit->paid_amount += $paidAmount;
                    }

                    // تحديث الحالة إذا تم سداد كل شيء
                    if ($activeCredit->remaining_amount <= 0) {
                        $activeCredit->status = 'paid';
                    }

                    $activeCredit->save();

                    // ربط الـ sortie بالكريديت
                    $sortie->update(['credit_id' => $activeCredit->id]);

                    // ✅ إنشاء payment إذا تم الدفع
                    if ($paidAmount > 0) {
                        Payment::create([
                            'credit_id' => $activeCredit->id,
                            'amount' => $paidAmount,
                            'payment_date' => now(),
                            'notes' => "Paiement lors de la sortie - {$motifFinal}",
                            'created_by' => auth()->id(),
                        ]);
                    }

                    Log::info('Crédit mis à jour automatiquement', [
                        'credit_id' => $activeCredit->id,
                        'client' => $client->name,
                        'old_amount' => $oldAmount,
                        'new_amount' => $activeCredit->amount,
                        'old_remaining' => $oldRemaining,
                        'new_remaining' => $activeCredit->remaining_amount,
                        'added_amount' => $totalPrice,
                        'paid_now' => $paidAmount,
                    ]);

                    $successMessage .= " et crédit mis à jour (Ajouté: " . number_format($totalPrice, 2) . " DH, Restant total: " . number_format($activeCredit->remaining_amount, 2) . " DH)";
                } else {
                    // ============================================
                    // ✅ إنشاء كريديت جديد (العميل ليس لديه كريديت نشط)
                    // ============================================

                    $credit = Credit::create([
                        'client_id' => $client->id,
                        'amount' => $totalPrice,
                        'paid_amount' => $paidAmount,
                        'remaining_amount' => $newDebt,
                        'reason' => $validated['credit_reason'] ?? "Sortie stock - {$motifFinal}",
                        'status' => $newDebt <= 0 ? 'paid' : 'active',
                        'user_id' => auth()->id(),
                        'created_by' => auth()->id(),
                    ]);

                    $sortie->update(['credit_id' => $credit->id]);

                    if ($paidAmount > 0) {
                        Payment::create([
                            'credit_id' => $credit->id,
                            'amount' => $paidAmount,
                            'payment_date' => now(),
                            'notes' => 'Paiement initial lors de la sortie de stock',
                            'created_by' => auth()->id(),
                        ]);
                    }

                    $successMessage .= " et crédit créé (Restant: " . number_format($newDebt, 2) . " DH)";
                }
            } else {
                // الدفع نقداً
                $successMessage .= " (Paiement comptant: " . number_format($totalPrice, 2) . " DH)";
            }

            DB::commit();

            // ✅ التوجيه حسب نوع الدفع
            if ($validated['payment_mode'] === 'credit') {
                // إذا كان دفع بالكريديت، التوجيه إلى صفحة عرض الكريديت
                $creditId = $activeCredit->id ?? $credit->id;

                return redirect()->route('credits.show', $creditId)
                    ->with('success', $successMessage);
            } else {
                // إذا كان دفع نقداً، التوجيه إلى قائمة الخروجات
                return redirect()->route('sorties.index')
                    ->with('success', $successMessage);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Sortie creation error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }



    public function show(Sortie $sortie)
    {
        $sortie->load(['product', 'credit.client', 'credit.payments']);
        return view('sorties.show', compact('sortie'));
    }

    public function edit(Sortie $sortie)
    {
        $products = Product::where('quantite', '>', 0)->get();
        return view('sorties.edit', compact('sortie', 'products'));
    }

    public function update(Request $request, Sortie $sortie)
    {
        // يمكنك إضافة منطق التحديث هنا إذا لزم الأمر
    }

    public function destroy(Sortie $sortie)
    {
        // يمكنك إضافة منطق الحذف هنا إذا لزم الأمر
    }
}
