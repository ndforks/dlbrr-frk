<?php

namespace App\Http\Controllers\Adherents;

use App\Http\Controllers\Controller;
use App\Services\AdherentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListAdherents extends Controller
{
    private AdherentService $service;

    public function __construct(AdherentService $service)
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

        return view('adherents.list', $data);
    }
}
