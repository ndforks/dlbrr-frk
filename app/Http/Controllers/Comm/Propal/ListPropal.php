<?php

namespace App\Http\Controllers\Comm\Propal;

use App\Http\Controllers\Controller;
use App\Models\Propal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListPropal extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $searchRef = GETPOST('search_ref', 'alpha');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Propal::with('societe');
        
        if ($searchAll) {
            $query->where(function($q) use ($searchAll) {
                $q->where('ref', 'like', "%{$searchAll}%");
            });
        }
        
        if ($searchRef) {
            $query->where('ref', 'like', "%{$searchRef}%");
        }
        
        $total = $query->count();
        $offset = $page * $limit;
        $propals = $query->orderBy('datep', 'DESC')->skip($offset)->take($limit)->get();
        
        return view('propal.list', ['propals' => $propals, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
