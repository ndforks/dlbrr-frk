<?php

namespace App\Http\Controllers\Bookcal;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class BookcalIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Bookcal/index.php');
    }
}
