<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class StockIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/product/stock/index.php');
    }
}