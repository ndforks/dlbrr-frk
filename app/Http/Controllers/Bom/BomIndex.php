<?php

namespace App\Http\Controllers\Bom;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class BomIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Bom/index.php');
    }
}
