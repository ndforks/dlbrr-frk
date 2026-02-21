<?php

namespace App\Http\Controllers\Commande;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowCommande extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Commande/card.php');
    }
}
