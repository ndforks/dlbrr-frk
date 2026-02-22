<?php

namespace App\Http\Controllers\Compta\Facture;

use App\Http\Controllers\Controller;
use App\Services\FactureService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListFacture extends Controller
{
    public function __construct(private readonly FactureService $service)
    {
    }

    public function __invoke(Request $request): View
    {
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);

        $filters = [
            'all' => $request->input('search_all'),
            'ref' => $request->input('search_ref'),
            'societe' => $request->input('search_societe'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('facture.list', $data);
    }
}
