<?php

namespace App\Http\Controllers\Fourn\Commande;
use App\Modules\Core\Classes\ExtraFields;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ShowCommande extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $user, $conf, $langs, $hookmanager;

        $langs->loadLangs(array('admin', 'orders', 'sendings', 'companies', 'bills', 'propal', 'receptions', 'supplier_proposal', 'products', 'stocks', 'productbatch'));
        
        if (isModEnabled('incoterm')) {
            $langs->load('incoterm');
        }

        $action = $request->input('action');
        $confirm = $request->input('confirm');
        $id = $request->integer('id', 0);
        $ref = $request->input('ref');
        $socid = $request->integer('socid', 0);

        if ($user->socid) {
            $socid = $user->socid;
        }

        $hookmanager->initHooks(array('ordersuppliercard', 'globalcard'));

        $object = new \CommandeFournisseur($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        if ($id > 0 || !empty($ref)) {
            $ret = $object->fetch($id, $ref);
            if ($ret < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
            $ret = $object->fetch_thirdparty();
            if ($ret < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }

        $permissiontoadd = $user->hasRight('fournisseur', 'commande', 'creer') || $user->hasRight('supplier_order', 'creer');
        $permissiontoedit = $permissiontoadd;

        restrictedArea($user, 'fournisseur', $object->id, 'commande_fournisseur', 'commande');

        return match($action) {
            'setref_supplier' => $this->setRefSupplier($request, $object),
            'set_incoterms' => $this->setIncoterms($request, $object),
            'setconditions' => $this->setConditions($request, $object),
            'setmode' => $this->setMode($request, $object),
            'setbankaccount' => $this->setBankAccount($request, $object),
            'setdate_livraison' => $this->setDateLivraison($request, $object),
            'classin' => $this->classIn($request, $object),
            'update_extras' => $this->updateExtras($request, $object),
            default => $this->show($request, $object),
        };
    }

    private function setRefSupplier(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user;

        // Early return for unauthorized access
        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $object->ref_supplier = $request->input('ref_supplier');
        $result = $object->update($user);

        // Early return for failure
        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect()->back();
        }

        return redirect()->back();
    }

    private function setIncoterms(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $result = $object->setIncoterms($request->integer('incoterm_id', 0), $request->input('incoterm_location'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setConditions(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $result = $object->setPaymentTerms($request->integer('cond_reglement_id', 0), $request->integer('cond_reglement_id_deposit_percent', 0));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setMode(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $result = $object->setPaymentMethods($request->integer('mode_reglement_id', 0));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setBankAccount(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $result = $object->setBankAccount($request->integer('fk_account', 0));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setDateLivraison(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $date_livraison = dol_mktime(0, 0, 0, $request->integer('date_livraisonmonth', 0), $request->integer('date_livraisonday', 0), $request->integer('date_livraisonyear', 0));
        $result = $object->setDeliveryDate($user, $date_livraison);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function classIn(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $result = $object->setProject($request->integer('projectid', 0));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function updateExtras(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user, $db;

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        $object->oldcopy = dol_clone($object, 2);
        $attribute_name = $request->input('attribute');
        $ret = $extrafields->setOptionalsFromPost(null, $object, $attribute_name);

        if ($ret >= 0) {
            $result = $object->updateExtraField($attribute_name, 'ORDER_SUPPLIER_MODIFY');
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }

        return redirect()->back();
    }

    private function show(Request $request, \CommandeFournisseur $object): View
    {
        global $db, $conf, $langs;

        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        $data = [
            'object' => $object,
            'extrafields' => $extrafields,
        ];

        return view('fourn.commande.card', $data);
    }
}
