<?php

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowHoliday extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Holiday/card.php');
    }
}
