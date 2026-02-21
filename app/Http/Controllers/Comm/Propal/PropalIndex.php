<?php

namespace App\Http\Controllers\Comm\Propal;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class PropalIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Comm/Propal/index.php');
    }
}
