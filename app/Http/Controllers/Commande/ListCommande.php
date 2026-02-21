<?php

namespace App\Http\Controllers\Commande;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListCommande extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/commande/list.php');
    }
}