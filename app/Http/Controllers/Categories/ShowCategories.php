<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowCategories extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $hookmanager;
        
        $action = GETPOST('action', 'alpha') ?: 'view';
        
        return match($action) {
            'create', 'add' => $this->create($request),
            'confirmed' => $this->confirmed($request),
            default => $this->create($request),
        };
    }
    
    private function create(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $hookmanager;
        
        $cancel = GETPOST('cancel', 'alpha');
        $origin = GETPOST('origin', 'alpha');
        $catorigin = GETPOSTINT('catorigin');
        $type = GETPOST('type', 'aZ09');
        $urlfrom = GETPOST('urlfrom', 'alpha');
        $backtopage = GETPOST('backtopage', 'alpha');
        
        $label = (string) GETPOST('label', 'alphanohtml');
        $description = (string) GETPOST('description', 'restricthtml');
        $color = preg_replace('/[^0-9a-f#]/i', '', (string) GETPOST('color', 'alphanohtml'));
        $position = GETPOSTISSET('position') ? GETPOSTINT('position') : 1;
        $visible = GETPOSTINT('visible');
        $parent = GETPOSTINT('parent');
        
        if (!$user->hasRight('categorie', 'lire')) {
            accessforbidden();
        }
        
        require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $object = new \Categorie($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $hookmanager->initHooks(array('categorycard'));
        
        $error = 0;
        
        // Handle form submission
        if ($request->isMethod('post') && GETPOST('action', 'alpha') == 'add' && $user->hasRight('categorie', 'creer')) {
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
        
        $urlfrom = GETPOST('urlfrom', 'alpha');
        $backtopage = GETPOST('backtopage', 'alpha');
        $type = GETPOST('type', 'aZ09');
        $origin = GETPOST('origin', 'alpha');
        
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
