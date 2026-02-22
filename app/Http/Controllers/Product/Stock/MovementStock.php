<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\Controller;
use App\Models\StockMouvement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovementStock extends Controller
{
    public function __invoke(Request $request): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $langs->loadLangs(['stocks', 'orders', 'suppliers', 'bills', 'propal', 'reception', 'productbatch', 'products', 'suppliers']);
        $hookmanager->initHooks(['stockmovementlist']);
        restrictedArea($user, 'stock');
        
        // Get recent stock movements
        $movements = StockMouvement::with(['product', 'entrepot', 'author'])
            ->orderBy('datem', 'desc')
            ->limit(100)
            ->get();
        
        return view('stock.movements', [
            'movements' => $movements,
            'langs' => $langs,
            'user' => $user,
        ]);
    }
}
