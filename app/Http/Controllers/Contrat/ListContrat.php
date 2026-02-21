<?php

namespace App\Http\Controllers\Contrat;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListContrat extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/contrat/list.php');
    }
}