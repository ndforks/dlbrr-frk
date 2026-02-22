<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricesController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        
        // Get special prices for this third party
        // This would typically query product_customer_price table
        return view('societe.prices', [
            'societe' => $societe,
        ]);
    }
}
