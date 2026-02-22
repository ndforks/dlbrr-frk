<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListLoan extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = $request->input('search_all');
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);
        
        $query = Loan::query();
        if ($searchAll) { $query->where('label', 'like', "%{$searchAll}%"); }
        
        $total = $query->count();
        $loans = $query->orderBy('datestart', 'DESC')->skip($page * $limit)->take($limit)->get();
        
        return view('loan.list', ['loans' => $loans, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }
}
