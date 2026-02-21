<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowProjet extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Projet/card.php');
    }
}
