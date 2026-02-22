<?php

namespace App\Http\Controllers\Fichinter;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class FichinterIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('fichinter.list');
    }
}
