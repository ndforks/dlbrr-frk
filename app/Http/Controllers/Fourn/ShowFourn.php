<?php

namespace App\Http\Controllers\Fourn;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowFourn extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/fourn/card.php');
    }
}
