<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowStock extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $action = GETPOST('action', 'aZ09') ?: 'view';
        $id = GETPOSTINT('id');
        $ref = GETPOST('ref', 'alpha');
        
        return match($action) {
            'add' => $this->store($request),
            'update' => $this->update($request, $id),
            'update_extras' => $this->updateExtras($request, $id),
            'confirm_delete' => $this->delete($request, $id),
            'create' => $this->create($request),
            'edit', 're-edit' => $this->edit($request, $id),
            default => $this->show($request, $id, $ref),
        };
    }
    
    private function show(Request $request, int $id, string $ref = ''): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $langs->loadLangs(['products', 'stocks', 'companies', 'categories']);
        $hookmanager->initHooks(['warehousecard', 'stocklist', 'globalcard']);
        
        $object = new \Entrepot($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($id > 0 || !empty($ref)) {
            $ret = $object->fetch($id, $ref);
            if ($ret <= 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }
        
        restrictedArea($user, 'stock', $id, 'entrepot&stock');
        
        return view('stock.show', [
            'object' => $object,
            'extrafields' => $extrafields,
            'action' => 'view'
        ]);
    }
    
    private function create(Request $request): View
    {
        global $db, $langs, $user, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $langs->loadLangs(['products', 'stocks', 'companies', 'categories']);
        $hookmanager->initHooks(['warehousecard', 'globalcard']);
        restrictedArea($user, 'stock', 0, 'entrepot&stock');
        
        $object = new \Entrepot($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        return view('stock.create', [
            'object' => $object,
            'extrafields' => $extrafields,
            'action' => 'create'
        ]);
    }
    
    private function edit(Request $request, int $id): View
    {
        global $db, $langs, $user, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $langs->loadLangs(['products', 'stocks', 'companies', 'categories']);
        $hookmanager->initHooks(['warehousecard', 'globalcard']);
        
        $object = new \Entrepot($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($id > 0) {
            $object->fetch($id);
        }
        
        restrictedArea($user, 'stock', $id, 'entrepot&stock');
        
        return view('stock.edit', [
            'object' => $object,
            'extrafields' => $extrafields,
            'action' => 'edit'
        ]);
    }
    
    private function store(Request $request): RedirectResponse
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('stock', 'creer')) {
            return redirect('/product/stock/list.php')->with('error', 'Permission denied');
        }
        
        require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $object = new \Entrepot($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $object->ref = GETPOST('ref', 'alpha');
        $object->fk_parent = GETPOSTINT('fk_parent');
        $object->fk_project = GETPOSTINT('projectid');
        $object->label = GETPOST('libelle', 'alpha');
        $object->description = GETPOST('desc', 'alpha');
        $object->statut = GETPOSTINT('statut');
        $object->lieu = GETPOST('lieu', 'alpha');
        $object->address = GETPOST('address', 'alpha');
        $object->zip = GETPOST('zipcode', 'alpha');
        $object->town = GETPOST('town', 'alpha');
        $object->country_id = GETPOSTINT('country_id');
        $object->phone = GETPOST('phone', 'alpha');
        $object->fax = GETPOST('fax', 'alpha');
        
        if (empty($object->label)) {
            setEventMessages($langs->trans('ErrorWarehouseRefRequired'), null, 'errors');
            return redirect('/product/stock/card.php?action=create');
        }
        
        $ret = $extrafields->setOptionalsFromPost(null, $object);
        if ($ret < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect('/product/stock/card.php?action=create');
        }
        
        $id = $object->create($user);
        if ($id > 0) {
            setEventMessages($langs->trans('RecordSaved'), null, 'mesgs');
            $categories = GETPOST('categories', 'array:int');
            $object->setCategories($categories);
            
            $backtopage = GETPOST('backtopage', 'alpha');
            if (!empty($backtopage)) {
                $backtopage = str_replace('__ID__', (string) $id, $backtopage);
                return redirect($backtopage);
            }
            return redirect("/product/stock/card.php?id={$id}");
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect('/product/stock/card.php?action=create');
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('stock', 'creer')) {
            return redirect("/product/stock/card.php?id={$id}")->with('error', 'Permission denied');
        }
        
        $cancel = GETPOST('cancel', 'alpha');
        if ($cancel) {
            return redirect("/product/stock/card.php?id={$id}");
        }
        
        require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $object = new \Entrepot($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($object->fetch($id)) {
            $object->label = GETPOST('libelle');
            $object->fk_parent = GETPOSTINT('fk_parent');
            $object->fk_project = GETPOSTINT('projectid');
            $object->description = GETPOST('desc', 'restricthtml');
            $object->statut = GETPOSTINT('statut');
            $object->lieu = GETPOST('lieu');
            $object->address = GETPOST('address');
            $object->zip = GETPOST('zipcode');
            $object->town = GETPOST('town');
            $object->country_id = GETPOSTINT('country_id');
            $object->phone = GETPOST('phone');
            $object->fax = GETPOST('fax');
            
            $ret = $extrafields->setOptionalsFromPost(null, $object, '@GETPOSTISSET');
            if ($ret < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                return redirect("/product/stock/card.php?action=edit&id={$id}");
            }
            
            $ret = $object->update($id, $user);
            if ($ret < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                return redirect("/product/stock/card.php?action=edit&id={$id}");
            }
            
            $categories = GETPOST('categories', 'array:int');
            $object->setCategories($categories);
            setEventMessages($langs->trans('RecordSaved'), null, 'mesgs');
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/product/stock/card.php?id={$id}");
    }
    
    private function updateExtras(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $object = new \Entrepot($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $object->fetch($id);
        $object->oldcopy = dol_clone($object, 2);
        
        $attribute_name = GETPOST('attribute', 'aZ09');
        $ret = $extrafields->setOptionalsFromPost(null, $object, $attribute_name);
        
        if ($ret >= 0) {
            $result = $object->updateExtraField($attribute_name, 'PRODUCT_MODIFY');
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/product/stock/card.php?id={$id}");
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('stock', 'supprimer')) {
            return redirect("/product/stock/card.php?id={$id}")->with('error', 'Permission denied');
        }
        
        $confirm = GETPOST('confirm');
        if ($confirm !== 'yes') {
            return redirect("/product/stock/card.php?id={$id}");
        }
        
        require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
        
        $object = new \Entrepot($db);
        $object->fetch($id);
        $result = $object->delete($user);
        
        if ($result > 0) {
            setEventMessages($langs->trans('RecordDeleted'), null, 'mesgs');
            return redirect('/product/stock/list.php?restore_lastsearch_values=1');
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect("/product/stock/card.php?id={$id}");
    }
}
