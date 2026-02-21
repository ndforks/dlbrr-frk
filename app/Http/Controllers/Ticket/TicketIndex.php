<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class TicketIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect('/ticket/list.php');
    }
}
