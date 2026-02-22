<?php

namespace App\Http\Controllers\Adherents;

use App\Http\Controllers\Controller;
use App\Models\Adherent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListAdherents extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = $request->input('search_all');
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);
        
        $query = Adherent::query();
        if ($searchAll) { $query->where(function($q) use ($searchAll) {
            $q->where('firstname', 'like', "%{$searchAll}%")->orWhere('lastname', 'like', "%{$searchAll}%");
        }); }
        
        $total = $query->count();
        $adherents = $query->orderBy('lastname', 'ASC')->skip($page * $limit)->take($limit)->get();
        
        return view('adherents.list', ['adherents' => $adherents, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
