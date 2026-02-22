<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListAsset extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = $request->input('search_all');
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);
        
        $query = Asset::query();
        if ($searchAll) { $query->where('ref', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $assets = $query->orderBy('ref', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('asset.list', ['assets' => $assets, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
