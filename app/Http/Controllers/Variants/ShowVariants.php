<?php

namespace App\Http\Controllers\Variants;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowVariants extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Variants/card.php');
    }
}
