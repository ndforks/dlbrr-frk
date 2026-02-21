<?php

namespace App\Http\Controllers\SupplierProposal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowSupplierProposal extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/supplier_proposal/card.php');
    }
}
