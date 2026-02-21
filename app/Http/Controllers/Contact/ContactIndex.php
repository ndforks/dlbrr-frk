<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ContactIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     * Since there's no index.php for contacts, redirect to the list
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Contact/list.php');
    }
}
