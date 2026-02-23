<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesIndex extends Controller
{
    private CategoryService $service;
    
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }
    
    public function __invoke(Request $request): View
    {
        global $user, $langs;
        
        // Early return for permission check
        if (!$user->hasRight('categorie', 'read')) {
            accessforbidden();
        }
        
        // Get category counts by type using service
        $countobjects = $this->service->getCountByType();
        
        // Get list of category types
        $arrayofcateg = $this->service->getCategoryTypes($countobjects, $langs);
        
        // Calculate number of modules not auto-enabled
        $nbmodulesnotautoenabled = count($GLOBALS['conf']->modules ?? []);
        $listofmodulesautoenabled = ['user', 'agenda', 'fckeditor', 'export', 'import'];
        
        foreach ($listofmodulesautoenabled as $moduleautoenable) {
            if (in_array($moduleautoenable, $GLOBALS['conf']->modules ?? [])) {
                $nbmodulesnotautoenabled--;
            }
        }
        
        return view('categories.index', [
            'arrayofcateg' => $arrayofcateg,
            'nbmodulesnotautoenabled' => $nbmodulesnotautoenabled,
        ]);
    }
}
