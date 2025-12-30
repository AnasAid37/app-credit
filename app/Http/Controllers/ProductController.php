<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // ✅ فلترة حسب المستخدم الحالي
        $query = Product::where('user_id', auth()->id());

        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('price', 'like', "%{$search}%")
                    ->orWhere('taille', 'like', "%{$search}%")
                    ->orWhere('marque', 'like', "%{$search}%");
            });
        }

        // الترتيب
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'DESC');

        $allowedSorts = ['price', 'taille', 'marque', 'quantite', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $query->orderBy($sort, $order);

        $products = $query->paginate(15);

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

        // التحقق من وجود منتج بنفس الماركة والحجم
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

        // التحقق من وجود منتج آخر بنفس الماركة والحجم
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

            // حذف حركات المخزون المرتبطة
            $product->sorties()->delete();
            $product->delete();

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Le produit a été supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Suppression en masse
     */
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
                'message' => "{$count} produit(s) supprimé(s) avec succès"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher le formulaire d'import
     */
    public function showImportForm()
    {
        return view('products.import');
    }

    /**
     * Importer depuis CSV
     */
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

            // Lire avec encodage UTF-8
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

                    // Validation
                    if (empty($taille)) {
                        $errors[] = "Ligne {$lineNumber}: Taille requise";
                        continue;
                    }

                    if ($price <= 0) {
                        $errors[] = "Ligne {$lineNumber}: Prix doit être supérieur à 0";
                        continue;
                    }

                    // Chercher produit existant
                    $product = Product::where('price', $price)
                        ->where('taille', $taille)
                        ->first();

                    if ($product) {
                        // Mettre à jour
                        $product->update([
                            'marque' => $marque ?: $product->marque,
                            'quantite' => $quantite,
                            'seuil_alerte' => $seuil_alerte
                        ]);
                        $updated++;
                    } else {
                        // Créer nouveau
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

    /**
     * Télécharger le modèle CSV
     */
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

            // Exemples
            fputcsv($file, ['299.99', '42', 'Nike', '50', '10']);
            fputcsv($file, ['450.00', '40', 'Adidas', '30', '5']);
            fputcsv($file, ['199.50', '38', 'Puma', '20', '8']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporter en CSV
     */
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
                    $product->stock_status_text,
                    $product->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
