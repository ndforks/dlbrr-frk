<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListDon extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Don/list.php');
    }
}
