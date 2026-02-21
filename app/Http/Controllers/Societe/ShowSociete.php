<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowSociete extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/societe/card.php');
    }
}