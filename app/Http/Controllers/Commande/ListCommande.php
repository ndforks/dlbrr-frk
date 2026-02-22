<?php

namespace App\Http\Controllers\Commande;

use App\Http\Controllers\Controller;
use App\Services\CommandeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListCommande extends Controller
{
    private CommandeService $service;

    public function __construct(CommandeService $service)
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
            'societe' => $request->input('search_societe'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('commande.list', $data);
    }
}
