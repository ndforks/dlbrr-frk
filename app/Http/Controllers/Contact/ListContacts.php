<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListContacts extends Controller
{
    /**
     * Handle the incoming request.
     * 
     * This demonstrates the refactoring pattern:
     * - Dolibarr code moved INTO the controller
     * - require statements replaced with use statements
     * - Hard-coded SQL replaced with Eloquent queries
     * - Dolibarr functions replaced with helper functions
     * - Returns a view response
     */
    public function __invoke(Request $request): View
    {
        // Get search parameters using helper functions (replaces GETPOST calls)
        $searchAll = $request->input('search_all');
        $searchLastname = $request->input('search_lastname');
        $searchFirstname = $request->input('search_firstname');
        $searchSociete = $request->input('search_societe');
        $searchEmail = $request->input('search_email');
        $searchPhone = $request->input('search_phone');
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);
        
        // Build Eloquent query (replaces raw SQL)
        $query = Contact::query()
            ->with('societe') // Eager load the company relationship
            ->select('llx_socpeople.*');
        
        // Apply search filters using Eloquent (replaces manual SQL WHERE clauses)
        if ($searchAll) {
            $query->where(function($q) use ($searchAll) {
                $q->where('firstname', 'like', "%{$searchAll}%")
                  ->orWhere('lastname', 'like', "%{$searchAll}%")
                  ->orWhere('email', 'like', "%{$searchAll}%")
                  ->orWhere('phone', 'like', "%{$searchAll}%")
                  ->orWhere('phone_mobile', 'like', "%{$searchAll}%");
            });
        }
        
        if ($searchLastname) {
            $query->where('lastname', 'like', "%{$searchLastname}%");
        }
        
        if ($searchFirstname) {
            $query->where('firstname', 'like', "%{$searchFirstname}%");
        }
        
        if ($searchEmail) {
            $query->where('email', 'like', "%{$searchEmail}%");
        }
        
        if ($searchPhone) {
            $query->where(function($q) use ($searchPhone) {
                $q->where('phone', 'like', "%{$searchPhone}%")
                  ->orWhere('phone_mobile', 'like', "%{$searchPhone}%");
            });
        }
        
        if ($searchSociete) {
            $query->whereHas('societe', function($q) use ($searchSociete) {
                $q->where('nom', 'like', "%{$searchSociete}%");
            });
        }
        
        // Get total count
        $total = $query->count();
        
        // Apply pagination
        $offset = $page * $limit;
        $contacts = $query->orderBy('lastname', 'ASC')
                          ->orderBy('firstname', 'ASC')
                          ->skip($offset)
                          ->take($limit)
                          ->get();
        
        // Prepare data for view
        $data = [
            'contacts' => $contacts,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => [
                'all' => $searchAll,
                'lastname' => $searchLastname,
                'firstname' => $searchFirstname,
                'societe' => $searchSociete,
                'email' => $searchEmail,
                'phone' => $searchPhone,
            ],
        ];
        
        // Return view (replaces direct print/echo statements)
        return view('contact.list', $data);
    }
}
