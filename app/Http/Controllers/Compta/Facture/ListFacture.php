<?php

namespace App\Http\Controllers\Compta\Facture;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ListFacture extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Compta/Facture/list.php');
    }
}
