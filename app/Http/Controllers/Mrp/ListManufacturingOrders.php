<?php

namespace App\Http\Controllers\Mrp;

use App\Http\Controllers\Controller;
use App\Services\MrpService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListManufacturingOrders extends Controller
{
    private MrpService $service;

    public function __construct(MrpService $service)
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

        return view('mrp.list', $data);
    }
}
