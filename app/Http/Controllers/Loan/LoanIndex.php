<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LoanIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect('/loan/list.php');
    }
}
