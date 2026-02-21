<?php

namespace App\Http\Controllers\Expedition;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ExpeditionIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect('/expedition/list.php');
    }
}
