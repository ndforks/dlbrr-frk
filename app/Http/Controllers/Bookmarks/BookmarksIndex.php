<?php

namespace App\Http\Controllers\Bookmarks;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class BookmarksIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Bookmarks/index.php');
    }
}
