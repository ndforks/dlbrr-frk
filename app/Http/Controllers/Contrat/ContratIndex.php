<?php

namespace App\Http\Controllers\Contrat;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ContratIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Contrat/index.php');
    }
}
