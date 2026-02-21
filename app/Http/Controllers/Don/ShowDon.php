<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowDon extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/don/card.php');
    }
}
