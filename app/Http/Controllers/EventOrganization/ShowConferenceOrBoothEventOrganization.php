<?php

namespace App\Http\Controllers\EventOrganization;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowConferenceOrBoothEventOrganization extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('EventOrganization/conferenceorbooth_card.php');
    }
}
