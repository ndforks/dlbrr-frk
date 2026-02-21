<?php

namespace App\Http\Controllers\Fichinter;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListFichinter extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/fichinter/list.php');
    }
}