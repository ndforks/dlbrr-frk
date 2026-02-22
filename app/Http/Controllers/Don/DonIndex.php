<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DonIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('don.list');
    }
}
