<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasSearchableList;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListTicket extends Controller
{
    use HasSearchableList;

    public function __invoke(Request $request): View
    {
        $pagination = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);
        
        $query = Ticket::query()->with('societe');
        $query = $this->applySearchFilters($query, $request);
        $query->orderBy('datec', 'DESC');
        
        $data = $this->buildListViewData($query, $pagination, $searchParams);
        
        return view('ticket.list', [
            'tickets' => $data['items'],
            'total' => $data['total'],
            'page' => $data['page'],
            'limit' => $data['limit'],
        ]);
    }

    protected function getSearchParams(Request $request): array
    {
        return [
            'all' => $request->input('search_all'),
            'ref' => $request->input('search_ref'),
        ];
    }

    protected function applySearchFilters(Builder $query, Request $request): Builder
    {
        $searchParams = $this->getSearchParams($request);
        
        // Early return if no search parameters
        if (empty(array_filter($searchParams))) {
            return $query;
        }
        
        if (!empty($searchParams['all'])) {
            $query = $this->applySearchAll($query, $searchParams['all'], ['ref', 'subject']);
        }
        
        if (!empty($searchParams['ref'])) {
            $query = $this->applyFieldSearch($query, $searchParams['ref'], 'ref');
        }
        
        return $query;
    }
}
