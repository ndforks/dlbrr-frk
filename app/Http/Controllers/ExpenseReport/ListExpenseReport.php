<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListExpenseReport extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/expense_report/list.php');
    }
}