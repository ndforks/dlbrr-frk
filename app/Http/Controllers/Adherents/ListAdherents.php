<?php

namespace App\Http\Controllers\Adherents;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListAdherents extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/adherents/list.php');
    }
}
