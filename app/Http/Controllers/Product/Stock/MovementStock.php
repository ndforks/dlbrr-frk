<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MovementStock extends Controller
{
    public function __invoke(): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $langs->loadLangs(['stocks', 'orders', 'suppliers', 'bills', 'propal', 'reception', 'productbatch', 'products', 'suppliers']);
        $hookmanager->initHooks(['stockmovementlist']);
        restrictedArea($user, 'stock');
        
        return view('stock.movements');
    }
}
