<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Services\AssetService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListAsset extends Controller
{
    private AssetService $service;

    public function __construct(AssetService $service)
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

        return view('asset.list', $data);
    }
}
