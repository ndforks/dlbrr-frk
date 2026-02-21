<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListAsset extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Asset/list.php');
    }
}
