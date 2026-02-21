<?php

namespace App\Http\Controllers\Compta\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListBank extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/compta/bank/list.php');
    }
}