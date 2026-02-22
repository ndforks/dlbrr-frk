<?php

namespace App\Http\Controllers\Categories;
use App\Modules\Core\Classes\ExtraFields;
use App\Modules\Categories\Classes\Categorie;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowCategories extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $hookmanager;
        
        $action = $request->input('action', 'view');
        
        return match($action) {
            'create', 'add' => $this->create($request),
            'confirmed' => $this->confirmed($request),
            default => $this->create($request),
        };
    }
    
    private function create(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $hookmanager;
        
        $cancel = $request->input('cancel');
        $origin = $request->input('origin');
        $catorigin = $request->integer('catorigin', 0);
        $type = $request->input('type');
        $urlfrom = $request->input('urlfrom');
        $backtopage = $request->input('backtopage');
        
        $label = (string) $request->input('label');
        $description = (string) $request->input('description');
        $color = preg_replace('/[^0-9a-f#]/i', '', (string) $request->input('color'));
        $position = $request->has('position') ? $request->integer('position', 0) : 1;
        $visible = $request->integer('visible', 0);
        $parent = $request->integer('parent', 0);
        
        if (!$user->hasRight('categorie', 'lire')) {
            accessforbidden();
        }
        
        require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';        $object = new Categorie($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $hookmanager->initHooks(array('categorycard'));
        
        $error = 0;
        
        // Handle form submission
        if ($request->isMethod('post') && $request->input('action') == 'add' && $user->hasRight('categorie', 'creer')) {
            if ($cancel) {
                return $this->handleCancel($urlfrom, $backtopage, $origin, $type);
            }
            
            $object->label = $label;
            $object->color = $color;
            $object->position = $position;
            $object->description = dol_htmlcleanlastbr($description);
            $object->socid = 0;
            $object->visible = $visible;
            $object->type = $type;
            
            if ($parent != "-1") {
                $object->fk_parent = $parent;
            }
            
            $ret = $extrafields->setOptionalsFromPost(null, $object);
            if ($ret < 0) {
                $error++;
            }
            
            if (!$object->label) {
                $error++;
                setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentities("Ref")), null, 'errors');
            }
            
            if (!$error) {
                $result = $object->create($user);
                if ($result > 0) {
                    return redirect("/categories/viewcat.php?id={$result}&type={$type}");
                } else {
                    setEventMessages($object->error, $object->errors, 'errors');
                }
            }
        }
        
        return view('categories.create', [
            'type' => $type,
            'label' => $label,
            'description' => $description,
            'color' => $color,
            'position' => $position,
            'parent' => $parent,
            'origin' => $origin,
            'catorigin' => $catorigin,
            'urlfrom' => $urlfrom,
            'backtopage' => $backtopage,
            'object' => $object,
            'extrafields' => $extrafields,
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
