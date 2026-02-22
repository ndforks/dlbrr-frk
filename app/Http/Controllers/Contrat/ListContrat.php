<?php

namespace App\Http\Controllers\Contrat;

use App\Http\Controllers\Controller;
use App\Services\ContratService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListContrat extends Controller
{
    private ContratService $service;

    public function __construct(ContratService $service)
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

        return view('contrat.list', $data);
    }
}
