<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListContacts extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Contact/list.php');
    }
}
