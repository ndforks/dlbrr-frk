<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ProjetIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/projet/index.php');
    }
}