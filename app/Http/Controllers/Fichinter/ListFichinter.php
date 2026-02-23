<?php

namespace App\Http\Controllers\Fichinter;

use App\Http\Controllers\Controller;
use App\Services\FichinterService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListFichinter extends Controller
{
    private FichinterService $service;

    public function __construct(FichinterService $service)
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

        return view('fichinter.list', $data);
    }
}
