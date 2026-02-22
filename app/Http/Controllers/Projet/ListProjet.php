<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\Controller;
use App\Services\ProjetService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListProjet extends Controller
{
    private ProjetService $service;

    public function __construct(ProjetService $service)
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
            'title' => $request->input('search_title'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('projet.list', $data);
    }
}
