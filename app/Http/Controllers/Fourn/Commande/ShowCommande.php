<?php

namespace App\Http\Controllers\Fourn\Commande;

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

        $action = GETPOST('action', 'alpha');
        $confirm = GETPOST('confirm', 'alpha');
        $id = GETPOSTINT('id');
        $ref = GETPOST('ref', 'alpha');
        $socid = GETPOSTINT('socid');

        if ($user->socid) {
            $socid = $user->socid;
        }

        $hookmanager->initHooks(array('ordersuppliercard', 'globalcard'));

        $object = new \CommandeFournisseur($db);
        $extrafields = new \ExtraFields($db);
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

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $object->ref_supplier = GETPOST('ref_supplier', 'alpha');
        $result = $object->update($user);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setIncoterms(Request $request, \CommandeFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'commande', 'creer') && !$user->hasRight('supplier_order', 'creer')) {
            accessforbidden();
        }

        $result = $object->setIncoterms(GETPOSTINT('incoterm_id'), GETPOST('incoterm_location', 'alpha'));

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

        $result = $object->setPaymentTerms(GETPOSTINT('cond_reglement_id'), GETPOSTINT('cond_reglement_id_deposit_percent'));

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

        $result = $object->setPaymentMethods(GETPOSTINT('mode_reglement_id'));

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

        $result = $object->setBankAccount(GETPOSTINT('fk_account'));

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

        $date_livraison = dol_mktime(0, 0, 0, GETPOSTINT('date_livraisonmonth'), GETPOSTINT('date_livraisonday'), GETPOSTINT('date_livraisonyear'));
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

        $result = $object->setProject(GETPOSTINT('projectid'));

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

        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        $object->oldcopy = dol_clone($object, 2);
        $attribute_name = GETPOST('attribute', 'aZ09');
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

        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        $data = [
            'object' => $object,
            'extrafields' => $extrafields,
        ];

        return view('fourn.commande.card', $data);
    }
}
