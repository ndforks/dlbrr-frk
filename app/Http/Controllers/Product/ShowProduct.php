<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowProduct extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Product/card.php');
    }
}
