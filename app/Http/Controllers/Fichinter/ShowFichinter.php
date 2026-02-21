<?php

namespace App\Http\Controllers\Fichinter;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowFichinter extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/fichinter/card.php');
    }
}
