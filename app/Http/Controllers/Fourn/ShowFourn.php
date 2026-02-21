<?php

namespace App\Http\Controllers\Fourn;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowFourn extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Fourn/card.php');
    }
}
