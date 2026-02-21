<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListTicket extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Ticket/list.php');
    }
}
