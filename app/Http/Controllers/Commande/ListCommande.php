<?php

namespace App\Http\Controllers\Commande;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListCommande extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Commande/list.php');
    }
}
