<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowCategories extends Controller
{
    private CategoryService $service;
    
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }
    
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        
        return match($action) {
            'create', 'add' => $this->create($request),
            'confirmed' => $this->confirmed($request),
            default => $this->create($request),
        };
    }
    
    private function create(Request $request): View|RedirectResponse
    {
        global $user, $langs;
        
        // Early return for permission check
        if (!$user->hasRight('categorie', 'lire')) {
            accessforbidden();
        }
        
        // Get request parameters
        $cancel = $request->input('cancel');
        $type = $request->input('type');
        $urlfrom = $request->input('urlfrom');
        $backtopage = $request->input('backtopage');
        $origin = $request->input('origin');
        
        // Handle form submission
        if ($request->isMethod('post') && $request->input('action') == 'add' && $user->hasRight('categorie', 'creer')) {
            // Early return for cancel
            if ($cancel) {
                return $this->handleCancel($urlfrom, $backtopage, $origin, $type);
            }
            
            $data = [
                'label' => $request->input('label'),
                'description' => $request->input('description'),
                'color' => preg_replace('/[^0-9a-f#]/i', '', $request->input('color', '')),
                'position' => $request->integer('position', 1),
                'visible' => $request->integer('visible', 0),
                'type' => $type,
                'fk_parent' => $request->integer('parent', -1) != -1 ? $request->integer('parent') : null,
            ];
            
            // Early return for validation error
            if (empty($data['label'])) {
                setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentities("Ref")), null, 'errors');
                return view('categories.create', array_merge($data, [
                    'origin' => $origin,
                    'catorigin' => $request->integer('catorigin', 0),
                    'urlfrom' => $urlfrom,
                    'backtopage' => $backtopage,
                ]));
            }
            
            $result = $this->service->create($data);
            
            if ($result) {
                return redirect("/categories/viewcat.php?id={$result}&type={$type}");
            }
            
            setEventMessages($langs->trans("ErrorCategoryCreation"), null, 'errors');
        }
        
        return view('categories.create', [
            'type' => $type,
            'label' => $request->input('label'),
            'description' => $request->input('description'),
            'color' => $request->input('color'),
            'position' => $request->integer('position', 1),
            'parent' => $request->integer('parent', 0),
            'origin' => $origin,
            'catorigin' => $request->integer('catorigin', 0),
            'urlfrom' => $urlfrom,
            'backtopage' => $backtopage,
        ]);
    }
    
    private function confirmed(Request $request): RedirectResponse
    {
        global $user;
        
        if (!$user->hasRight('categorie', 'creer')) {
            accessforbidden();
        }
        
        $urlfrom = $request->input('urlfrom');
        $backtopage = $request->input('backtopage');
        $type = $request->input('type');
        $origin = $request->input('origin');
        
        if ($urlfrom) {
            return redirect($urlfrom);
        } elseif ($backtopage) {
            return redirect($backtopage);
        }
        
        return redirect("/categories/categorie_list.php?leftmenu=cat&type={$type}");
    }
    
    private function handleCancel($urlfrom, $backtopage, $origin, $type): RedirectResponse
    {
        if ($urlfrom) {
            return redirect($urlfrom);
        } elseif ($backtopage) {
            return redirect($backtopage);
        } elseif ($origin) {
            return redirect("/categories/viewcat.php?id={$origin}&type={$type}");
        }
        
        return redirect("/categories/categorie_list.php?leftmenu=cat&type={$type}");
    }
}
