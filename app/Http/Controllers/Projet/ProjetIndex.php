<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ProjetIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('projet.list');
    }
}
