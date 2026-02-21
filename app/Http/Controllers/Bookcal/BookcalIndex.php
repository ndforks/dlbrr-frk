<?php

namespace App\Http\Controllers\Bookcal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class BookcalIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/bookcal/index.php');
    }
}