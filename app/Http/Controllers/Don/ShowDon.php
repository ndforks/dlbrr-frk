<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowDon extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Don/card.php');
    }
}
