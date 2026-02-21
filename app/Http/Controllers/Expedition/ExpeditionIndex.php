<?php

namespace App\Http\Controllers\Expedition;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ExpeditionIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Expedition/index.php');
    }
}
