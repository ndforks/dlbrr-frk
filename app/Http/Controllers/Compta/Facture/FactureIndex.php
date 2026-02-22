<?php

namespace App\Http\Controllers\Compta\Facture;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class FactureIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('facture.list');
    }
}
