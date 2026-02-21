<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DonIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/don/index.php');
    }
}