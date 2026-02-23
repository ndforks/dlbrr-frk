<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListProduct extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
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
            'label' => $request->input('search_label'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('product.list', $data);
    }
}
