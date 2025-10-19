<?php

namespace App\Http\Controllers;

use App\Models\Product;

class AlertController extends Controller
{
    public function index()
    {
        $alertProducts = Product::where('quantite', '<=', \DB::raw('seuil_alerte'))
            ->orderBy('quantite')
            ->get();

        return view('alerts.index', compact('alertProducts'));
    }

    public function getAlertCount()
    {
        $count = Product::where('quantite', '<=', \DB::raw('seuil_alerte'))->count();
        
        return response()->json(['count' => $count]);
    }
}