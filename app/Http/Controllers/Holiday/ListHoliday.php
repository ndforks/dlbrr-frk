<?php

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListHoliday extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Holiday::query();
        if ($searchAll) { $query->where('ref', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $holidays = $query->orderBy('date_create', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('holiday.list', ['holidays' => $holidays, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
