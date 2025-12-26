<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('price', 'like', "%{$search}%")
                  ->orWhere('taille', 'like', "%{$search}%")
                  ->orWhere('marque', 'like', "%{$search}%");
            });
        }
        
        // الترتيب
        $sort = $request->get('sort', 'price');
        $order = $request->get('order', 'asc');
        
        $allowedSorts = ['price', 'taille', 'marque', 'quantite'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'price';
        }
        
        $query->orderBy($sort, $order);
        
        $products = $query->paginate(10);
        
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric|min:0',
            'taille' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'quantite' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // التحقق من وجود منتج بنفس السعر والحجم
        $existingProduct = Product::where('price', $request->price)
            ->where('taille', $request->taille)
            ->first();

        if ($existingProduct) {
            return redirect()->back()->with('warning', 'Un produit avec ce prix et cette taille existe déjà.')->withInput();
        }

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Le produit a été ajouté avec succès.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric|min:0',
            'taille' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'quantite' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // التحقق من وجود منتج آخر بنفس السعر والحجم
        $existingProduct = Product::where('price', $request->price)
            ->where('taille', $request->taille)
            ->where('id', '!=', $product->id)
            ->first();

        if ($existingProduct) {
            return redirect()->back()->with('warning', 'Un autre produit avec ce prix et cette taille existe déjà.')->withInput();
        }

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Le produit a été modifié avec succès.');
    }

    public function destroy(Product $product)
    {
        // حذف حركات المخزون المرتبطة أولاً
        $product->sorties()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Le produit a été supprimé avec succès.');
    }
}
