<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class Home extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/index.php');
    }
}
