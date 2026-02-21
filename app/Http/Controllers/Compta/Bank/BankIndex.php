<?php

namespace App\Http\Controllers\Compta\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class BankIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/compta/bank/index.php');
    }
}