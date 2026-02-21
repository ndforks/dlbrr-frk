<?php

namespace App\Http\Controllers\EventOrganization;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class EventOrganizationIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('EventOrganization/index.php');
    }
}
