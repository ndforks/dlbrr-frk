<?php

namespace App\Http\Controllers\Bom;

use App\Http\Controllers\Controller;
use App\Models\Bom;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListBom extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = $request->input('search_all');
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);
        
        $query = Bom::query();
        if ($searchAll) { $query->where('ref', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $boms = $query->orderBy('ref', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('bom.list', ['boms' => $boms, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
