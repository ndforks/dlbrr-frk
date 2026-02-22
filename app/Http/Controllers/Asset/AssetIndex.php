<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AssetIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('asset.list');
    }
}
