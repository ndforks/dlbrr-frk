<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\Controller;
use App\Models\ExpenseReport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListExpenseReport extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = ExpenseReport::query();
        if ($searchAll) { $query->where('ref', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $reports = $query->orderBy('date_create', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('expensereport.list', ['reports' => $reports, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
