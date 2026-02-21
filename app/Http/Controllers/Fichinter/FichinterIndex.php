<?php

namespace App\Http\Controllers\Fichinter;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class FichinterIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Fichinter/index.php');
    }
}
