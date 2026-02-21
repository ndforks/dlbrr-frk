<?php

namespace App\Http\Controllers\Mrp;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowManufacturingOrder extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Mrp/mo_card.php');
    }
}
