<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class SocieteIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Societe/index.php');
    }
}
