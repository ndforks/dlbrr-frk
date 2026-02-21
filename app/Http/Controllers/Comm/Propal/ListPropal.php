<?php

namespace App\Http\Controllers\Comm\Propal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListPropal extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/comm/propal/list.php');
    }
}