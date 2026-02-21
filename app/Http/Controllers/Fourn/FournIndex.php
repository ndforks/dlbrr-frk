<?php

namespace App\Http\Controllers\Fourn;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class FournIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Fourn/index.php');
    }
}
