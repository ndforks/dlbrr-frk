<?php

namespace App\Http\Controllers\Variants;

use App\Http\Controllers\Controller;
use App\Services\ProductAttributeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListVariants extends Controller
{
    /**
     * Constructor with dependency injection
     */
    public function __construct(
        protected ProductAttributeService $attributeService
    ) {
    }

    /**
     * Handle the incoming request.
     * Displays list of product attributes with search/filter capabilities.
     */
    public function __invoke(Request $request): View
    {
        global $conf, $langs, $user, $hookmanager;
        
        $langs->loadLangs(['products', 'other']);
        
        // Early return for unauthorized access
        if (!isModEnabled('variants')) {
            accessforbidden('Module not enabled');
        }
        
        if ($user->socid > 0) {
            accessforbidden();
        }
        
        $hookmanager->initHooks(['productattributelist']);
        restrictedArea($user, 'variants');
        
        $limit = $request->integer('limit', 0) ?: $conf->liste_limit;
        $sortfield = $request->input('sortfield', 't.position');
        $sortorder = $request->input('sortorder', 'ASC');
        $page = $request->integer('page', 0) ?: 0;
        $offset = $limit * $page;
        
        // Build search parameters
        $search = [
            'ref' => $request->input('search_ref'),
            'label' => $request->input('search_label'),
        ];
        
        // Get entity ID
        $entity = getEntity('product');
        if (is_array($entity)) {
            $entity = reset($entity);
        }
        
        // Use service to fetch data with Eloquent
        $result = $this->attributeService->getList(
            $search,
            $sortfield,
            $sortorder,
            $limit,
            $offset,
            (int) $entity
        );
        
        return view('variants.list', [
            'attributes' => $result['attributes'],
            'total' => $result['total'],
            'limit' => $limit,
            'page' => $page,
            'sortfield' => $sortfield,
            'sortorder' => $sortorder,
            'search' => $search,
        ]);
    }
}
