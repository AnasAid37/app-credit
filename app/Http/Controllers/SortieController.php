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
        // ✅ تغيير من product_id إلى produit_id
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',  // ✅ هنا التغيير
            'quantite' => 'required|integer|min:1',
            'nom_client' => 'required|string|max:255',
            'motif_sortie' => 'required|string',
            'autre_motif' => 'nullable|string|max:500',
            'payment_mode' => 'required|in:cash,credit',
            'credit_paid_amount' => 'nullable|numeric|min:0',
            'credit_reason' => 'nullable|string|max:1000',
        ], [
            // ✅ رسائل خطأ مخصصة
            'product_id.required' => 'Veuillez sélectionner un produit',
            'product_id.exists' => 'Le produit sélectionné n\'existe pas',
            'quantite.required' => 'La quantité est requise',
            'quantite.min' => 'La quantité doit être au moins 1',
            'nom_client.required' => 'Le nom du client est requis',
            'motif_sortie.required' => 'Le motif de sortie est requis',
            'payment_mode.required' => 'Le mode de paiement est requis',
        ]);

        try {
            DB::beginTransaction();

            // ✅ استخدام produit_id من الـ validated
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

            // ✅ إنشاء Sortie - استخدام product_id (اسم العمود)
            $sortie = Sortie::create([
                'product_id' => $product->id,  // ✅ product_id في الجدول
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

            // Gestion du crédit
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

                $paidAmount = $validated['credit_paid_amount'] ?? 0;
                $remainingAmount = $totalPrice - $paidAmount;

                $credit = Credit::create([
                    'client_id' => $client->id,
                    'amount' => $totalPrice,
                    'paid_amount' => $paidAmount,
                    'remaining_amount' => $remainingAmount,
                    'reason' => $validated['credit_reason'] ?? "Sortie stock - {$motifFinal}",
                    'status' => $remainingAmount <= 0 ? 'paid' : 'active',
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

                $successMessage .= " et crédit créé pour {$validated['nom_client']} (Restant: " . number_format($remainingAmount, 2) . " DH)";
            } else {
                $successMessage .= " (Paiement comptant: " . number_format($totalPrice, 2) . " DH)";
            }

            DB::commit();

            return redirect()->route('sorties.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Sortie creation error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage())
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
