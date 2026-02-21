<?php

namespace App\Http\Controllers\Expedition;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListExpedition extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Expedition/list.php');
    }
}
