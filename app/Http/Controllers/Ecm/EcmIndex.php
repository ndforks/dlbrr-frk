<?php

namespace App\Http\Controllers\Ecm;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class EcmIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/ecm/index.php');
    }
}
