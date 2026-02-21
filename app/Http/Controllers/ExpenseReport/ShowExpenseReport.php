<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowExpenseReport extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/expense_report/card.php');
    }
}
