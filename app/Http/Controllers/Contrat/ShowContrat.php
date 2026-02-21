<?php

namespace App\Http\Controllers\Contrat;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowContrat extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/contrat/card.php');
    }
}