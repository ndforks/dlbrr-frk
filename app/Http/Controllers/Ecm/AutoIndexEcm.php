<?php

namespace App\Http\Controllers\Ecm;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AutoIndexEcm extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/ecm/index_auto.php');
    }
}
