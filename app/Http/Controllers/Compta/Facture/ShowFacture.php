<?php

namespace App\Http\Controllers\Compta\Facture;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowFacture extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/compta/facture/card.php');
    }
}