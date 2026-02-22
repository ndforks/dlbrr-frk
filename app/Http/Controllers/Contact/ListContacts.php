<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasSearchableList;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListContacts extends Controller
{
    use HasSearchableList;

    public function __invoke(Request $request): View
    {
        $pagination = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);
        
        $query = Contact::query()
            ->with('societe')
            ->select('llx_socpeople.*');
        
        $query = $this->applySearchFilters($query, $request);
        
        $query->orderBy('lastname', 'ASC')->orderBy('firstname', 'ASC');
        
        $data = $this->buildListViewData($query, $pagination, $searchParams);
        
        return view('contact.list', [
            'contacts' => $data['items'],
            'total' => $data['total'],
            'page' => $data['page'],
            'limit' => $data['limit'],
            'search' => $data['search'],
        ]);
    }

    protected function getSearchParams(Request $request): array
    {
        return [
            'all' => $request->input('search_all'),
            'lastname' => $request->input('search_lastname'),
            'firstname' => $request->input('search_firstname'),
            'societe' => $request->input('search_societe'),
            'email' => $request->input('search_email'),
            'phone' => $request->input('search_phone'),
        ];
    }

    protected function applySearchFilters(Builder $query, Request $request): Builder
    {
        $searchParams = $this->getSearchParams($request);
        
        // Early return if no search parameters
        if (empty(array_filter($searchParams))) {
            return $query;
        }
        
        // Apply search all filter
        if (!empty($searchParams['all'])) {
            $query = $this->applySearchAll($query, $searchParams['all'], [
                'firstname',
                'lastname',
                'email',
                'phone',
                'phone_mobile',
            ]);
        }
        
        // Apply individual field filters
        if (!empty($searchParams['lastname'])) {
            $query = $this->applyFieldSearch($query, $searchParams['lastname'], 'lastname');
        }
        
        if (!empty($searchParams['firstname'])) {
            $query = $this->applyFieldSearch($query, $searchParams['firstname'], 'firstname');
        }
        
        if (!empty($searchParams['email'])) {
            $query = $this->applyFieldSearch($query, $searchParams['email'], 'email');
        }
        
        if (!empty($searchParams['phone'])) {
            $query->where(function($q) use ($searchParams) {
                $q->where('phone', 'like', "%{$searchParams['phone']}%")
                  ->orWhere('phone_mobile', 'like', "%{$searchParams['phone']}%");
            });
        }
        
        if (!empty($searchParams['societe'])) {
            $query = $this->applyRelationshipSearch($query, $searchParams['societe'], 'societe', 'nom');
        }
        
        return $query;
    }
}
