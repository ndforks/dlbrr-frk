<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class StockIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Product/Stock/index.php');
    }
}
