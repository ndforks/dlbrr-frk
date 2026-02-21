<?php

namespace App\Http\Controllers\Comm\Propal;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowPropal extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Comm/Propal/card.php');
    }
}
