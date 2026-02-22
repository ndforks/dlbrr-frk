<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\Controller;
use App\Models\Entrepot;
use Illuminate\Http\Response;
use Illuminate\View\View;

class StockIndex extends Controller
{
    public function __invoke(): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $langs->loadLangs(['stocks', 'productbatch']);
        $hookmanager->initHooks(['stockindex']);
        restrictedArea($user, 'stock');
        
        // Fetch all warehouses
        $warehouses = Entrepot::where('entity', '=', $conf->entity)
            ->orderBy('ref', 'asc')
            ->get();
        
        return view('stock.index', [
            'warehouses' => $warehouses,
            'langs' => $langs,
            'user' => $user,
        ]);
    }
}
