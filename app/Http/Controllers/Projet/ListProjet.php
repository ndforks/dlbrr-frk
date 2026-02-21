<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListProjet extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $searchRef = GETPOST('search_ref', 'alpha');
        $searchTitle = GETPOST('search_title', 'alpha');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Projet::with('societe');
        
        if ($searchAll) {
            $query->where(function($q) use ($searchAll) {
                $q->where('ref', 'like', "%{$searchAll}%")
                  ->orWhere('title', 'like', "%{$searchAll}%");
            });
        }
        
        if ($searchRef) {
            $query->where('ref', 'like', "%{$searchRef}%");
        }
        
        if ($searchTitle) {
            $query->where('title', 'like', "%{$searchTitle}%");
        }
        
        $total = $query->count();
        $offset = $page * $limit;
        $projets = $query->orderBy('ref', 'DESC')
                         ->skip($offset)
                         ->take($limit)
                         ->get();
        
        return view('projet.list', [
            'projets' => $projets,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => [
                'all' => $searchAll,
                'ref' => $searchRef,
                'title' => $searchTitle,
            ],
        ]);
    }
}
