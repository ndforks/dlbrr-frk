<?php

namespace App\Http\Controllers\Adherents;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class AdherentsIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Adherents/index.php');
    }
}
