<?php

namespace App\Http\Controllers\Compta\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class BankIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect('/compta/bank/list.php');
    }
}
