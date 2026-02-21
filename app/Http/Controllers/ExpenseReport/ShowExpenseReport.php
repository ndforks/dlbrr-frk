<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowExpenseReport extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('ExpenseReport/card.php');
    }
}
