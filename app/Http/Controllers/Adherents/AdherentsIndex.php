<?php

namespace App\Http\Controllers\Adherents;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AdherentsIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('adherents.list');
    }
}
