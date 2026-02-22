<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\Controller;
use App\Models\Don;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListDon extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = $request->input('search_all');
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);
        
        $query = Don::with('societe');
        if ($searchAll) { $query->where('ref', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $dons = $query->orderBy('datedon', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('don.list', ['dons' => $dons, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
