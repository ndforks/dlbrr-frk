<?php

namespace App\Http\Controllers\SupplierProposal;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class ShowSupplierProposal extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('SupplierProposal/card.php');
    }
}
