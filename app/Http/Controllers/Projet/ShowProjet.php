<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowProjet extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/projet/card.php');
    }
}
