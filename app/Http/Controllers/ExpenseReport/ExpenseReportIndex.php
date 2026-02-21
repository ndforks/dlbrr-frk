<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ExpenseReportIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('ExpenseReport/index.php');
    }
}
