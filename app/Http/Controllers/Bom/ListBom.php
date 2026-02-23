<?php

namespace App\Http\Controllers\Bom;

use App\Http\Controllers\Controller;
use App\Services\BomService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListBom extends Controller
{
    private BomService $service;

    public function __construct(BomService $service)
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

        return view('bom.list', $data);
    }
}
