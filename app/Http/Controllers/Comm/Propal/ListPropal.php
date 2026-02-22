<?php

namespace App\Http\Controllers\Comm\Propal;

use App\Http\Controllers\Controller;
use App\Services\PropalService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListPropal extends Controller
{
    private PropalService $service;

    public function __construct(PropalService $service)
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

        return view('propal.list', $data);
    }
}
