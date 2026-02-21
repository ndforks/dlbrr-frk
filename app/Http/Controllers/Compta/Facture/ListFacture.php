<?php

namespace App\Http\Controllers\Compta\Facture;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListFacture extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = GETPOST('search_all', 'alphanohtml');
        $searchRef = GETPOST('search_ref', 'alpha');
        $searchSociete = GETPOST('search_societe', 'alpha');
        $page = GETPOSTINT('page');
        $limit = GETPOSTINT('limit') ?: 25;
        
        $query = Facture::with('societe');
        
        if ($searchAll) {
            $query->where(function($q) use ($searchAll) {
                $q->where('ref', 'like', "%{$searchAll}%")
                  ->orWhere('ref_client', 'like', "%{$searchAll}%");
            });
        }
        
        if ($searchRef) {
            $query->where('ref', 'like', "%{$searchRef}%");
        }
        
        if ($searchSociete) {
            $query->whereHas('societe', function($q) use ($searchSociete) {
                $q->where('nom', 'like', "%{$searchSociete}%");
            });
        }
        
        $total = $query->count();
        $offset = $page * $limit;
        $factures = $query->orderBy('datef', 'DESC')
                          ->skip($offset)
                          ->take($limit)
                          ->get();
        
        return view('facture.list', [
            'factures' => $factures,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => [
                'all' => $searchAll,
                'ref' => $searchRef,
                'societe' => $searchSociete,
            ],
        ]);
    }
}
