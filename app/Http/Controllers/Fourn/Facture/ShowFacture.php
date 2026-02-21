<?php

namespace App\Http\Controllers\Fourn\Facture;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ShowFacture extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $user, $conf, $langs, $hookmanager;

        $langs->loadLangs(array('bills', 'compta', 'suppliers', 'companies', 'products', 'banks', 'admin'));
        
        if (isModEnabled('incoterm')) {
            $langs->load('incoterm');
        }

        $id = (GETPOSTINT('facid') ? GETPOSTINT('facid') : GETPOSTINT('id'));
        $action = GETPOST('action', 'aZ09');
        $confirm = GETPOST("confirm");
        $ref = GETPOST('ref', 'alpha');
        $cancel = GETPOST('cancel', 'alpha');

        $hookmanager->initHooks(array('invoicesuppliercard', 'globalcard'));

        $object = new \FactureFournisseur($db);
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

        $permissiontoadd = $user->hasRight('fournisseur', 'facture', 'creer') || $user->hasRight('supplier_invoice', 'creer');
        $permissiontoedit = $permissiontoadd;

        restrictedArea($user, 'fournisseur', $object->id, 'facture_fourn', 'facture');

        return match($action) {
            'setref_supplier' => $this->setRefSupplier($request, $object),
            'setconditions' => $this->setConditions($request, $object),
            'set_incoterms' => $this->setIncoterms($request, $object),
            'setmode' => $this->setMode($request, $object),
            'setbankaccount' => $this->setBankAccount($request, $object),
            'setvatreversecharge' => $this->setVatReverseCharge($request, $object),
            'settransportmode' => $this->setTransportMode($request, $object),
            'setlabel' => $this->setLabel($request, $object),
            'setdatef' => $this->setDateF($request, $object),
            'setdate_lim_reglement' => $this->setDateLimReglement($request, $object),
            'classin' => $this->classIn($request, $object),
            'update_extras' => $this->updateExtras($request, $object),
            default => $this->show($request, $object),
        };
    }

    private function setRefSupplier(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $object->ref_supplier = GETPOST('ref_supplier', 'alpha');
        $result = $object->update($user);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setConditions(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $result = $object->setPaymentTerms(GETPOSTINT('cond_reglement_id'), GETPOSTINT('cond_reglement_id_deposit_percent'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setIncoterms(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $result = $object->setIncoterms(GETPOSTINT('incoterm_id'), GETPOST('incoterm_location', 'alpha'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setMode(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $result = $object->setPaymentMethods(GETPOSTINT('mode_reglement_id'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setBankAccount(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $result = $object->setBankAccount(GETPOSTINT('fk_account'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setVatReverseCharge(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $result = $object->setVatReverseCharge(GETPOSTINT('vat_reverse_charge'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setTransportMode(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $result = $object->setTransportMode(GETPOSTINT('transport_mode_id'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setLabel(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $object->label = GETPOST('label', 'alpha');
        $result = $object->update($user);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setDateF(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $datef = dol_mktime(0, 0, 0, GETPOSTINT('datefmonth'), GETPOSTINT('datefday'), GETPOSTINT('datefyear'));
        $result = $object->setDate($user, $datef);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setDateLimReglement(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $date_lim_reglement = dol_mktime(0, 0, 0, GETPOSTINT('date_lim_reglementmonth'), GETPOSTINT('date_lim_reglementday'), GETPOSTINT('date_lim_reglementyear'));
        $result = $object->setPaymentDueDate($user, $date_lim_reglement);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function classIn(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $result = $object->setProject(GETPOSTINT('projectid'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function updateExtras(Request $request, \FactureFournisseur $object): RedirectResponse
    {
        global $user, $db;

        if (!$user->hasRight('fournisseur', 'facture', 'creer') && !$user->hasRight('supplier_invoice', 'creer')) {
            accessforbidden();
        }

        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        $object->oldcopy = dol_clone($object, 2);
        $attribute_name = GETPOST('attribute', 'aZ09');
        $ret = $extrafields->setOptionalsFromPost(null, $object, $attribute_name);

        if ($ret >= 0) {
            $result = $object->updateExtraField($attribute_name, 'BILL_SUPPLIER_MODIFY');
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }

        return redirect()->back();
    }

    private function show(Request $request, \FactureFournisseur $object): View
    {
        global $db, $conf, $langs;

        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        $data = [
            'object' => $object,
            'extrafields' => $extrafields,
        ];

        return view('fourn.facture.card', $data);
    }
}
