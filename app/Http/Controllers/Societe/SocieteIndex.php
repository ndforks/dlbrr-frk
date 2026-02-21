<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class SocieteIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect('/societe/list.php');
    }
}
