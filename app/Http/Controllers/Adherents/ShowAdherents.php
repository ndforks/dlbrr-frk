<?php

namespace App\Http\Controllers\Adherents;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowAdherents extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/adherents/card.php');
    }
}
