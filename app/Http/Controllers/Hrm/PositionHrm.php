<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class PositionHrm extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Hrm/position.php');
    }
}
