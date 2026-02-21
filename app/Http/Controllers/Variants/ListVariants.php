<?php

namespace App\Http\Controllers\Variants;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListVariants extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Variants/list.php');
    }
}
