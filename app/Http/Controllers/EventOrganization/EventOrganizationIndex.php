<?php

namespace App\Http\Controllers\EventOrganization;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class EventOrganizationIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/eventorganization/index.php');
    }
}
