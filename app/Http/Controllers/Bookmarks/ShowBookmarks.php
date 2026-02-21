<?php

namespace App\Http\Controllers\Bookmarks;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowBookmarks extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Bookmarks/card.php');
    }
}
