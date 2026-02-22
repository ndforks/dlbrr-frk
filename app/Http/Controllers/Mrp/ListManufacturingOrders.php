<?php

namespace App\Http\Controllers\Mrp;

use App\Http\Controllers\Controller;
use App\Models\Mrp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListManufacturingOrders extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = $request->input('search_all');
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);
        
        $query = Mrp::query();
        if ($searchAll) { $query->where('ref', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $mrps = $query->orderBy('ref', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('mrp.list', ['mrps' => $mrps, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
