<?php

namespace App\Http\Controllers\Bom;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListBom extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/bom/list.php');
    }
}