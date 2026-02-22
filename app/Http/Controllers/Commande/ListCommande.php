<?php

namespace App\Http\Controllers\Commande;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListCommande extends Controller
{
    public function __invoke(Request $request): View
    {
        $searchAll = $request->input('search_all');
        $searchRef = $request->input('search_ref');
        $searchSociete = $request->input('search_societe');
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);
        
        $query = Commande::with('societe');
        
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
        $commandes = $query->orderBy('date_commande', 'DESC')
                          ->skip($offset)
                          ->take($limit)
                          ->get();
        
        return view('commande.list', [
            'commandes' => $commandes,
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
