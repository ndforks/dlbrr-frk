<?php

namespace App\Http\Controllers\Fourn\Facture;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowFacture extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/fourn/facture/card.php');
    }
}
