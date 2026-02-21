<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListProjet extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/projet/list.php');
    }
}