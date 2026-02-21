<?php

namespace App\Http\Controllers\Ecm;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class AutoIndexEcm extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Ecm/index_auto.php');
    }
}
