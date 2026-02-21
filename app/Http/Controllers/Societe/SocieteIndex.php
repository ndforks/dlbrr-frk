<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class SocieteIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/societe/index.php');
    }
}