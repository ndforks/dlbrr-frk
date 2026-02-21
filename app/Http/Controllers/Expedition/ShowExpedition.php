<?php

namespace App\Http\Controllers\Expedition;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowExpedition extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/expedition/card.php');
    }
}