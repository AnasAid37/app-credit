<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sortie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SortieController extends Controller
{
    public function index(Request $request)
    {
        $query = Sortie::with(['product', 'user']);

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

        $sorties = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('sorties.index', compact('sorties'));
    }

    public function create()
    {
        $products = Product::where('quantite', '>', 0)->get();
        return view('sorties.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produit_id' => 'required|exists:products,id',
            'quantite' => 'required|integer|min:1',
            'nom_client' => 'required|string|max:255',
            'motif_sortie' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::findOrFail($request->produit_id);

        if ($product->quantite < $request->quantite) {
            return redirect()->back()->with('warning', 
                "Stock insuffisant. Il reste seulement {$product->quantite} unité(s) en stock.")->withInput();
        }

        DB::transaction(function () use ($request, $product) {
            // تحديث كمية المنتج
            $product->decrement('quantite', $request->quantite);

            // تسجيل حركة الخروج
            Sortie::create([
                'product_id' => $request->produit_id,
                'user_id' => auth()->id(),
                'quantite' => $request->quantite,
                'nom_client' => $request->nom_client,
                'motif_sortie' => $request->motif_sortie,
                'prix_total' => $product->price * $request->quantite
            ]);
        });

        return redirect()->route('sorties.create')->with('success', 
            "Sortie de stock enregistrée avec succès. Produit : {$product->marque} ({$product->taille})");
    }

    public function show(Sortie $sortie)
    {
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