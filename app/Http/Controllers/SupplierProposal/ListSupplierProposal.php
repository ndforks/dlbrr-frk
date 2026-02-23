<?php

namespace App\Http\Controllers\SupplierProposal;

use App\Http\Controllers\Controller;
use App\Services\SupplierProposalService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListSupplierProposal extends Controller
{
    private SupplierProposalService $service;
    
    public function __construct(SupplierProposalService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Handle the incoming request.
     * Displays list of supplier proposals with search/filter capabilities.
     */
    public function __invoke(Request $request): View
    {
        $filters = [
            'ref' => $request->input('search_ref'),
            'company' => $request->input('search_company'),
            'montant_ht' => $request->input('search_montant_ht'),
            'status' => $request->input('search_status'),
            'socid' => $request->integer('socid', 0),
            'sortfield' => $request->input('sortfield', 'date_creation'),
            'sortorder' => $request->input('sortorder', 'DESC'),
        ];
        
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', config('app.default_list_limit', 25));
        
        $result = $this->service->list($filters, $page, $limit);
        
        return view('supplier_proposal.list', [
            'proposals' => $result['data'],
            'total' => $result['total'],
            'limit' => $limit,
            'page' => $page,
            'sortfield' => $filters['sortfield'],
            'sortorder' => $filters['sortorder'],
            'search_ref' => $filters['ref'],
            'search_company' => $filters['company'],
            'search_montant_ht' => $filters['montant_ht'],
            'search_status' => $filters['status'],
        ]);
    }
}
