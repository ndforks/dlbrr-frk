<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowCategories extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Categories/card.php');
    }
}
