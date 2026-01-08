<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Liste des catégories
     */
    public function index()
    {
        $categories = Category::forUser()
            ->withCount('products')
            ->latest()
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Page d'ajout d'une catégorie
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'couleur' => 'required|string',
            'icone' => 'required|string',
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'nom' => $request->nom,
            'description' => $request->description,
            'couleur' => $request->couleur,
            'icone' => $request->icone,
            'actif' => true,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie ajoutée avec succès');
    }

    /**
     * Page de modification
     */
    public function edit(Category $category)
    {
        // Vérification des permissions
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Mettre à jour la catégorie
     */
    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'couleur' => 'required|string',
            'icone' => 'required|string',
            'actif' => 'boolean',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }

    /**
     * Supprimer la catégorie
     */
    public function destroy(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        // Vérifier s'il y a des produits
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une catégorie contenant des produits');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }

    /**
     * Afficher les détails de la catégorie avec ses produits
     */
    public function show(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $products = $category->products()
            ->orderBy('nom')
            ->paginate(20);

        $stats = [
            'total_products' => $category->products()->count(),
            'total_stock_value' => $category->products()->sum(DB::raw('quantite * price')),
            'low_stock_count' => $category->products()->lowStock()->count(),
            'out_of_stock' => $category->products()->where('quantite', 0)->count(),
        ];

        return view('categories.show', compact('category', 'products', 'stats'));
    }
}