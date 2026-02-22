<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasSearchableList;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListProduct extends Controller
{
    use HasSearchableList;

    public function __invoke(Request $request): View
    {
        $pagination = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);
        
        $query = Product::query();
        $query = $this->applySearchFilters($query, $request);
        $query->orderBy('ref', 'ASC');
        
        $data = $this->buildListViewData($query, $pagination, $searchParams);
        
        return view('product.list', [
            'products' => $data['items'],
            'total' => $data['total'],
            'page' => $data['page'],
            'limit' => $data['limit'],
            'search' => $data['search'],
        ]);
    }

    protected function getSearchParams(Request $request): array
    {
        return [
            'all' => $request->input('search_all'),
            'ref' => $request->input('search_ref'),
            'label' => $request->input('search_label'),
        ];
    }

    protected function applySearchFilters(Builder $query, Request $request): Builder
    {
        $searchParams = $this->getSearchParams($request);
        
        // Early return if no search parameters
        if (empty(array_filter($searchParams))) {
            return $query;
        }
        
        if (!empty($searchParams['all'])) {
            $query = $this->applySearchAll($query, $searchParams['all'], ['ref', 'label', 'description']);
        }
        
        if (!empty($searchParams['ref'])) {
            $query = $this->applyFieldSearch($query, $searchParams['ref'], 'ref');
        }
        
        if (!empty($searchParams['label'])) {
            $query = $this->applyFieldSearch($query, $searchParams['label'], 'label');
        }
        
        return $query;
    }
}
