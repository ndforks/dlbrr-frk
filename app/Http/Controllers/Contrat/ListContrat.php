<?php

namespace App\Http\Controllers\Contrat;

use App\Http\Controllers\Controller;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListContrat extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Contrat::with('societe');
        if ($searchAll) { $query->where('ref', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $contrats = $query->orderBy('ref', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('contrat.list', ['contrats' => $contrats, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
