<?php

namespace App\Http\Controllers\Fourn;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class FournIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/fourn/index.php');
    }
}
