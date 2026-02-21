<?php

namespace App\Http\Controllers\Comm\Propal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowPropal extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/comm/propal/card.php');
    }
}