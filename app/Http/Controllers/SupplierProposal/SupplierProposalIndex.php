<?php

namespace App\Http\Controllers\SupplierProposal;

use App\Http\Controllers\DolibarrController;
use Illuminate\Http\Response;

class SupplierProposalIndex extends DolibarrController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('SupplierProposal/index.php');
    }
}
