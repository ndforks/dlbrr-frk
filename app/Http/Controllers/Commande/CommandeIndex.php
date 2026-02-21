<?php

namespace App\Http\Controllers\Commande;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class CommandeIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect('/commande/list.php');
    }
}
