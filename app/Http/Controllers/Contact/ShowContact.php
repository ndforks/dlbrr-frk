<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowContact extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Contact/card.php');
    }
}
