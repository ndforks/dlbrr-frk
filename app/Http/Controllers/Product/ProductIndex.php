<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ProductIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Product/index.php');
    }
}
