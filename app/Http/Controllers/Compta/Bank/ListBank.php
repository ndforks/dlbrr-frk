<?php

namespace App\Http\Controllers\Compta\Bank;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListBank extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Compta/Bank/list.php');
    }
}
