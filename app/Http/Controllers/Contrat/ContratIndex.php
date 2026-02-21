<?php

namespace App\Http\Controllers\Contrat;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ContratIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect('/contrat/list.php');
    }
}
