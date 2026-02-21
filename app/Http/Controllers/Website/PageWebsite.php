<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class PageWebsite extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Website/page.php');
    }
}
