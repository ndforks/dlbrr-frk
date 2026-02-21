<?php

namespace App\Http\Controllers\Expedition;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListExpedition extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/expedition/list.php');
    }
}