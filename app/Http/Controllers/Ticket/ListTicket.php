<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListTicket extends Controller
{
    private TicketService $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): View
    {
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);

        $filters = [
            'all' => $request->input('search_all'),
            'ref' => $request->input('search_ref'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('ticket.list', $data);
    }
}
