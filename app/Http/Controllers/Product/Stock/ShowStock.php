<?php

namespace App\Http\Controllers\Product\Stock;

use App\Http\Controllers\Controller;
use App\Modules\Product\Stock\Classes\Entrepot;
use App\Modules\Core\Classes\ExtraFields;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowStock extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        $ref = $request->input('ref');
        
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
        
        
        $langs->loadLangs(['products', 'stocks', 'companies', 'categories']);
        $hookmanager->initHooks(['warehousecard', 'stocklist', 'globalcard']);
        
        $object = new Entrepot($db);
        $extrafields = new ExtraFields($db);
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
        
        
        $langs->loadLangs(['products', 'stocks', 'companies', 'categories']);
        $hookmanager->initHooks(['warehousecard', 'globalcard']);
        restrictedArea($user, 'stock', 0, 'entrepot&stock');
        
        $object = new Entrepot($db);
        $extrafields = new ExtraFields($db);
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
        
        
        $langs->loadLangs(['products', 'stocks', 'companies', 'categories']);
        $hookmanager->initHooks(['warehousecard', 'globalcard']);
        
        $object = new Entrepot($db);
        $extrafields = new ExtraFields($db);
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
        
        
        $object = new Entrepot($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $object->ref = $request->input('ref');
        $object->fk_parent = $request->integer('fk_parent', 0);
        $object->fk_project = $request->integer('projectid', 0);
        $object->label = $request->input('libelle');
        $object->description = $request->input('desc');
        $object->statut = $request->integer('statut', 0);
        $object->lieu = $request->input('lieu');
        $object->address = $request->input('address');
        $object->zip = $request->input('zipcode');
        $object->town = $request->input('town');
        $object->country_id = $request->integer('country_id', 0);
        $object->phone = $request->input('phone');
        $object->fax = $request->input('fax');
        
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
            $categories = $request->input('categories');
            $object->setCategories($categories);
            
            $backtopage = $request->input('backtopage');
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
        
        $cancel = $request->input('cancel');
        if ($cancel) {
            return redirect("/product/stock/card.php?id={$id}");
        }
        
        
        $object = new Entrepot($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($object->fetch($id)) {
            $object->label = $request->input('libelle');
            $object->fk_parent = $request->integer('fk_parent', 0);
            $object->fk_project = $request->integer('projectid', 0);
            $object->description = $request->input('desc');
            $object->statut = $request->integer('statut', 0);
            $object->lieu = $request->input('lieu');
            $object->address = $request->input('address');
            $object->zip = $request->input('zipcode');
            $object->town = $request->input('town');
            $object->country_id = $request->integer('country_id', 0);
            $object->phone = $request->input('phone');
            $object->fax = $request->input('fax');
            
            $ret = $extrafields->setOptionsFromPost($request, $object);
            if ($ret < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                return redirect("/product/stock/card.php?action=edit&id={$id}");
            }
            
            $ret = $object->update($user);
            if ($ret < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                return redirect("/product/stock/card.php?action=edit&id={$id}");
            }
            
            $categories = $request->input('categories');
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
        
        
        $object = new Entrepot($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $object->fetch($id);
        $object->oldcopy = dol_clone($object, 2);
        
        $attribute_name = $request->input('attribute');
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
        
        $confirm = $request->input('confirm');
        if ($confirm !== 'yes') {
            return redirect("/product/stock/card.php?id={$id}");
        }
        
        
        $object = new Entrepot($db);
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
