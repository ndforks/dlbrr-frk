<?php

namespace App\Http\Controllers\Fichinter;

use App\Http\Controllers\Controller;
use App\Models\Fichinter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListFichinter extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Fichinter::with('societe');
        if ($searchAll) { $query->where('ref', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $fichinters = $query->orderBy('ref', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('fichinter.list', ['fichinters' => $fichinters, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
