<?php

namespace App\Http\Controllers\Compta\Bank;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class BankIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Compta/Bank/index.php');
    }
}
