<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ExpenseReportIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/expense_report/index.php');
    }
}