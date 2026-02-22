<?php

namespace App\Http\Controllers\Variants;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class VariantsIndex extends Controller
{
    /**
     * Handle the incoming request.
     * Redirects to list page.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('variants.list');
    }
}
