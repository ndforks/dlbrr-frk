<?php

namespace App\Http\Controllers\Comm\Propal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class PropalIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('propal.list');
    }
}
