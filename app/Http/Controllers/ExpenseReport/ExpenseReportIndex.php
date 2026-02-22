<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ExpenseReportIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('expensereport.list');
    }
}
