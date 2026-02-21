<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListDon extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/don/list.php');
    }
}