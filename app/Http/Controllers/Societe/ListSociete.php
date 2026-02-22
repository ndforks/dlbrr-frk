<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Services\SocieteService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListSociete extends Controller
{
    private SocieteService $service;

    public function __construct(SocieteService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): View
    {
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);

        $filters = [
            'all' => $request->input('search_all'),
            'nom' => $request->input('search_nom'),
            'town' => $request->input('search_town'),
            'zip' => $request->input('search_zip'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('societe.list', $data);
    }
}
