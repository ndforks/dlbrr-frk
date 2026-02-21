<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class LoanIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Loan/index.php');
    }
}
