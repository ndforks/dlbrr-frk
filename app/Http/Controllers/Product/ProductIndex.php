<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ProductIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('product.list');
    }
}
