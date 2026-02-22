<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\Controller;
use App\Services\DonService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListDon extends Controller
{
    private DonService $service;

    public function __construct(DonService $service)
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

        return view('don.list', $data);
    }
}
