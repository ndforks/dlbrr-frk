<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ProjetIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Projet/index.php');
    }
}
