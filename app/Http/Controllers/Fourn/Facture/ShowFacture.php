<?php

namespace App\Http\Controllers\Fourn\Facture;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowFacture extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Fourn/Facture/card.php');
    }
}
