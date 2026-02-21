<?php

namespace App\Http\Controllers\Accountancy;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class AccountancyIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Accountancy/index.php');
    }
}
