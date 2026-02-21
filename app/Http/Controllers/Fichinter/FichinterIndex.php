<?php

namespace App\Http\Controllers\Fichinter;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class FichinterIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/fichinter/index.php');
    }
}