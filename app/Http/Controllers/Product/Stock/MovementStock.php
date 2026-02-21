<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class MovementStock extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Product/Stock/mouvement.php');
    }
}
