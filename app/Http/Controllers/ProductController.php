<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('user_id', auth()->id());

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('price', 'like', "%{$search}%")
                  ->orWhere('taille', 'like', "%{$search}%")
                  ->orWhere('marque', 'like', "%{$search}%");
            });
        }

        $sortColumn = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'DESC');
        $query->orderBy($sortColumn, $sortOrder);

        $products = $query->paginate(20);

        $products->getCollection()->transform(function ($product) {
            if ($product->quantite == 0) {
                $product->stock_status = 'danger';
            } elseif ($product->quantite <= $product->seuil_alerte) {
                $product->stock_status = 'warning';
            } else {
                $product->stock_status = 'success';
            }
            return $product;
        });

        $categories = Category::where('user_id', auth()->id())
            ->where('actif', true)
            ->withCount('products')
            ->orderBy('nom')
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * ✅ عرض تفاصيل المنتج
     */
    public function show(Product $product)
    {
        // ✅ تحميل العلاقات (حذفنا entrees لأنها غير موجودة)
        $product->load('category', 'sorties.credit');
        
        // حساب حالة المخزون
        if ($product->quantite == 0) {
            $product->stock_status = 'danger';
            $product->stock_status_text = 'Rupture de stock';
        } elseif ($product->quantite <= $product->seuil_alerte) {
            $product->stock_status = 'warning';
            $product->stock_status_text = 'Stock faible';
        } else {
            $product->stock_status = 'success';
            $product->stock_status_text = 'En stock';
        }
        
        return view('products.show', compact('product'));
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

        $existingProduct = Product::where('taille', $request->taille)
            ->where('marque', $request->marque)
            ->first();

        if ($existingProduct) {
            return redirect()->back()
                ->with('warning', 'Un produit avec cette marque et taille existe déjà.')
                ->withInput();
        }

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Le produit a été ajouté avec succès.');
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

        $existingProduct = Product::where('taille', $request->taille)
            ->where('marque', $request->marque)
            ->where('id', '!=', $product->id)
            ->first();

        if ($existingProduct) {
            return redirect()->back()
                ->with('warning', 'Un autre produit avec cette marque et taille existe déjà.')
                ->withInput();
        }

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Le produit a été modifié avec succès.');
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            $product->sorties()->delete();
            $product->delete();

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Le produit a été supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        try {
            DB::beginTransaction();

            $products = Product::whereIn('id', $request->ids)->get();
            $count = $products->count();

            foreach ($products as $product) {
                $product->sorties()->delete();
                $product->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$count} produit(s) supprimé(s) avec succès",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showImportForm()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'Veuillez sélectionner un fichier',
            'file.mimes' => 'Le fichier doit être au format CSV',
            'file.max' => 'Le fichier ne doit pas dépasser 5 Mo'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();

            $content = file_get_contents($path);
            if (!mb_check_encoding($content, 'UTF-8')) {
                $content = mb_convert_encoding($content, 'UTF-8', 'auto');
            }

            $tempPath = sys_get_temp_dir() . '/temp_products_' . time() . '.csv';
            file_put_contents($tempPath, $content);

            $data = array_map('str_getcsv', file($tempPath));
            unlink($tempPath);

            $header = array_shift($data);

            $imported = 0;
            $updated = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($data as $index => $row) {
                $lineNumber = $index + 2;

                if (empty(array_filter($row))) continue;

                try {
                    $price = (float) str_replace([',', ' '], ['', ''], $row[0] ?? '0');
                    $taille = trim($row[1] ?? '');
                    $marque = trim($row[2] ?? '');
                    $quantite = (int) ($row[3] ?? 0);
                    $seuil_alerte = (int) ($row[4] ?? 5);

                    if (empty($taille)) {
                        $errors[] = "Ligne {$lineNumber}: Taille requise";
                        continue;
                    }

                    if ($price <= 0) {
                        $errors[] = "Ligne {$lineNumber}: Prix doit être supérieur à 0";
                        continue;
                    }

                    $product = Product::where('price', $price)
                        ->where('taille', $taille)
                        ->first();

                    if ($product) {
                        $product->update([
                            'marque' => $marque ?: $product->marque,
                            'quantite' => $quantite,
                            'seuil_alerte' => $seuil_alerte
                        ]);
                        $updated++;
                    } else {
                        Product::create([
                            'price' => $price,
                            'taille' => $taille,
                            'marque' => $marque ?: null,
                            'quantite' => $quantite,
                            'seuil_alerte' => $seuil_alerte
                        ]);
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Ligne {$lineNumber}: {$e->getMessage()}";
                }
            }

            DB::commit();

            $message = "{$imported} produit(s) importé(s), {$updated} mis à jour";

            if (!empty($errors)) {
                $message .= ". Erreurs: " . implode(', ', array_slice($errors, 0, 2));
                if (count($errors) > 2) {
                    $message .= " et " . (count($errors) - 2) . " autre(s)";
                }
            }

            return redirect()->route('products.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'modele_produits.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Prix',
                'Taille',
                'Marque',
                'Quantité',
                'Seuil Alerte'
            ]);

            fputcsv($file, ['299.99', '42', 'Nike', '50', '10']);
            fputcsv($file, ['450.00', '40', 'Adidas', '30', '5']);
            fputcsv($file, ['199.50', '38', 'Puma', '20', '8']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        $fileName = 'produits_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID',
                'Prix',
                'Taille',
                'Marque',
                'Quantité',
                'Seuil Alerte',
                'Statut Stock',
                'Date Création'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    number_format($product->price, 2, '.', ''),
                    $product->taille,
                    $product->marque ?? '',
                    $product->quantite,
                    $product->seuil_alerte,
                    $product->stock_status_text ?? 'N/A',
                    $product->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}