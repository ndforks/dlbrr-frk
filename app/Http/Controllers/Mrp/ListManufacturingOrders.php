<?php

namespace App\Http\Controllers\Mrp;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListManufacturingOrders extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Mrp/mo_list.php');
    }
}
