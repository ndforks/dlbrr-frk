<?php

namespace App\Http\Controllers\Variants;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowVariants extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/variants/card.php');
    }
}
