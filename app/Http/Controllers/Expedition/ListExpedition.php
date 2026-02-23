<?php

namespace App\Http\Controllers\Expedition;

use App\Http\Controllers\Controller;
use App\Services\ExpeditionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListExpedition extends Controller
{
    private ExpeditionService $service;

    public function __construct(ExpeditionService $service)
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

        return view('expedition.list', $data);
    }
}
