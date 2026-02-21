<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\View\View;

class StockIndex extends Controller
{
    public function __invoke(): View
    {
        global $db, $langs, $user, $hookmanager;
        
        $langs->loadLangs(['stocks', 'productbatch']);
        $hookmanager->initHooks(['stockindex']);
        restrictedArea($user, 'stock');
        
        return view('stock.index');
    }
}
