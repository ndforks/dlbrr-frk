<?php

namespace App\Http\Controllers\Fourn\Commande;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class CommandeIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Fourn/Commande/card.php');
    }
}
