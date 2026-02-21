<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListProduct extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $searchRef = GETPOST('search_ref', 'alpha');
        $searchLabel = GETPOST('search_label', 'alpha');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Product::query();
        
        if ($searchAll) {
            $query->where(function($q) use ($searchAll) {
                $q->where('ref', 'like', "%{$searchAll}%")
                  ->orWhere('label', 'like', "%{$searchAll}%")
                  ->orWhere('description', 'like', "%{$searchAll}%");
            });
        }
        
        if ($searchRef) {
            $query->where('ref', 'like', "%{$searchRef}%");
        }
        
        if ($searchLabel) {
            $query->where('label', 'like', "%{$searchLabel}%");
        }
        
        $total = $query->count();
        $offset = $page * $limit;
        $products = $query->orderBy('ref', 'ASC')
                          ->skip($offset)
                          ->take($limit)
                          ->get();
        
        return view('product.list', [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => [
                'all' => $searchAll,
                'ref' => $searchRef,
                'label' => $searchLabel,
            ],
        ]);
    }
}
