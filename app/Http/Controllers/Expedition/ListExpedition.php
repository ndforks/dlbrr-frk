<?php

namespace App\Http\Controllers\Expedition;

use App\Http\Controllers\Controller;
use App\Models\Expedition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListExpedition extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Expedition::with('societe');
        if ($searchAll) {
            $query->where('ref', 'like', "%{$searchAll}%");
        }
        
        $total = $query->count();
        $expeditions = $query->orderBy('date_expedition', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('expedition.list', ['expeditions' => $expeditions, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
