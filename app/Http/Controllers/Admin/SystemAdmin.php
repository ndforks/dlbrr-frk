<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class SystemAdmin extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Admin/System/index.php');
    }
}
