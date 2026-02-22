<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListLoan extends Controller
{
    private LoanService $service;

    public function __construct(LoanService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): View
    {
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);

        $filters = [
            'all' => $request->input('search_all'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('loan.list', $data);
    }
}
