<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListTicket extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $searchRef = GETPOST('search_ref', 'alpha');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Ticket::with('societe');
        
        if ($searchAll) {
            $query->where(function($q) use ($searchAll) {
                $q->where('ref', 'like', "%{$searchAll}%")
                  ->orWhere('subject', 'like', "%{$searchAll}%");
            });
        }
        
        if ($searchRef) {
            $query->where('ref', 'like', "%{$searchRef}%");
        }
        
        $total = $query->count();
        $offset = $page * $limit;
        $tickets = $query->orderBy('datec', 'DESC')->skip($offset)->take($limit)->get();
        
        return view('ticket.list', ['tickets' => $tickets, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
