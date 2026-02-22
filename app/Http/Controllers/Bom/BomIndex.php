<?php

namespace App\Http\Controllers\Bom;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class BomIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('bom.list');
    }
}
