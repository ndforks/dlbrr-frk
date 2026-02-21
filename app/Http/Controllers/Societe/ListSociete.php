<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListSociete extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $searchName = GETPOST('search_nom', 'alpha');
        $searchTown = GETPOST('search_town', 'alpha');
        $searchZip = GETPOST('search_zip', 'alpha');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Societe::query();
        
        if ($searchAll) {
            $query->where(function($q) use ($searchAll) {
                $q->where('nom', 'like', "%{$searchAll}%")
                  ->orWhere('name_alias', 'like', "%{$searchAll}%")
                  ->orWhere('email', 'like', "%{$searchAll}%")
                  ->orWhere('code_client', 'like', "%{$searchAll}%");
            });
        }
        
        if ($searchName) {
            $query->where('nom', 'like', "%{$searchName}%");
        }
        
        if ($searchTown) {
            $query->where('town', 'like', "%{$searchTown}%");
        }
        
        if ($searchZip) {
            $query->where('zip', 'like', "%{$searchZip}%");
        }
        
        $total = $query->count();
        $offset = $page * $limit;
        $societes = $query->orderBy('nom', 'ASC')
                          ->skip($offset)
                          ->take($limit)
                          ->get();
        
        return view('societe.list', [
            'societes' => $societes,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => [
                'all' => $searchAll,
                'nom' => $searchName,
                'town' => $searchTown,
                'zip' => $searchZip,
            ],
        ]);
    }
}
