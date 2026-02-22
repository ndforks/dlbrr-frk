<?php

namespace App\Http\Controllers\Fourn;
use App\Modules\Core\Classes\ExtraFields;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ShowFourn extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $user, $conf, $langs, $hookmanager;

        $langs->loadLangs(array('accountancy', 'companies', 'suppliers', 'products', 'bills', 'orders', 'commercial'));

        $action = GETPOST('action', 'aZ09');
        $cancel = GETPOST('cancel', 'alpha');
        $id = (GETPOSTINT('socid') ? GETPOSTINT('socid') : GETPOSTINT('id'));

        if ($user->socid) {
            $id = $user->socid;
        }

        $hookmanager->initHooks(array('thirdpartysupplier', 'globalcard'));
        restrictedArea($user, 'societe&fournisseur', $id, '&societe', '', 'rowid');

        $object = new \Fournisseur($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        $permissiontoadd = $user->hasRight('societe', 'creer');
        $permissiontoeditextra = $permissiontoadd;

        if (GETPOST('attribute', 'aZ09') && isset($extrafields->attributes[$object->table_element]['perms'][GETPOST('attribute', 'aZ09')])) {
            $permissiontoeditextra = dol_eval((string) $extrafields->attributes[$object->table_element]['perms'][GETPOST('attribute', 'aZ09')]);
        }

        restrictedArea($user, 'societe', $id, '&societe', '', 'fk_soc', 'rowid', 0);

        if ($object->id > 0) {
            if (!($object->fournisseur > 0) || !$user->hasRight("fournisseur", "lire")) {
                accessforbidden();
            }
        }

        return match($action) {
            'setsupplieraccountancycodegeneral' => $this->setSupplierAccountancyCodeGeneral($request, $id),
            'setsupplieraccountancycode' => $this->setSupplierAccountancyCode($request, $id),
            'settva_intra' => $this->setTvaIntra($request, $id),
            'setconditions' => $this->setConditions($request, $id),
            'setmode' => $this->setMode($request, $id),
            'setbankaccount' => $this->setBankAccount($request, $id),
            'setsupplier_order_min_amount' => $this->setSupplierOrderMinAmount($request, $id),
            'update_extras' => $this->updateExtras($request, $id, $permissiontoeditextra),
            default => $this->show($request, $id),
        };
    }

    private function setSupplierAccountancyCodeGeneral(Request $request, int $id): RedirectResponse
    {
        global $db, $user;

        if (!$user->hasRight('societe', 'creer')) {
            accessforbidden();
        }

        $object = new \Fournisseur($db);
        $object->fetch($id);
        $object->accountancy_code_supplier_general = GETPOST("supplieraccountancycodegeneral");
        $result = $object->update($object->id, $user, 1, 0, 1);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setSupplierAccountancyCode(Request $request, int $id): RedirectResponse
    {
        global $db, $user;

        if (!$user->hasRight('societe', 'creer')) {
            accessforbidden();
        }

        $object = new \Fournisseur($db);
        $object->fetch($id);
        $object->code_compta_fournisseur = GETPOST("supplieraccountancycode");
        $result = $object->update($object->id, $user, 1, 0, 1);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setTvaIntra(Request $request, int $id): RedirectResponse
    {
        global $db, $user;

        if (!$user->hasRight('societe', 'creer')) {
            accessforbidden();
        }

        $object = new \Fournisseur($db);
        $object->fetch($id);
        $object->tva_intra = GETPOST("tva_intra");
        $result = $object->update($object->id, $user, 1, 0, 0);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setConditions(Request $request, int $id): RedirectResponse
    {
        global $db, $user;

        if (!$user->hasRight('societe', 'creer')) {
            accessforbidden();
        }

        $object = new \Fournisseur($db);
        $object->fetch($id);
        $result = $object->setPaymentTerms(GETPOSTINT('cond_reglement_supplier_id'), GETPOSTINT('cond_reglement_supplier_id_deposit_percent'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setMode(Request $request, int $id): RedirectResponse
    {
        global $db, $user;

        if (!$user->hasRight('societe', 'creer')) {
            accessforbidden();
        }

        $object = new \Fournisseur($db);
        $object->fetch($id);
        $result = $object->setPaymentMethods(GETPOSTINT('mode_reglement_supplier_id'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setBankAccount(Request $request, int $id): RedirectResponse
    {
        global $db, $user;

        if (!$user->hasRight('societe', 'creer')) {
            accessforbidden();
        }

        $object = new \Fournisseur($db);
        $object->fetch($id);
        $result = $object->setBankAccount(GETPOSTINT('fk_account'));

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function setSupplierOrderMinAmount(Request $request, int $id): RedirectResponse
    {
        global $db, $user;

        if (!$user->hasRight('societe', 'creer')) {
            accessforbidden();
        }

        $object = new \Fournisseur($db);
        $object->fetch($id);
        $object->supplier_order_min_amount = price2num(GETPOST('supplier_order_min_amount', 'alpha'));
        $result = $object->update($object->id, $user);

        if ($result < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
        }

        return redirect()->back();
    }

    private function updateExtras(Request $request, int $id, bool $permissiontoeditextra): RedirectResponse
    {
        global $db, $user;

        if (!$permissiontoeditextra) {
            accessforbidden();
        }

        $object = new \Fournisseur($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        $object->fetch($id);
        $object->oldcopy = dol_clone($object, 2);

        $attribute_name = GETPOST('attribute', 'aZ09');
        $ret = $extrafields->setOptionalsFromPost(null, $object, $attribute_name);

        if ($ret >= 0) {
            $result = $object->updateExtraField($attribute_name, 'COMPANY_MODIFY');
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }

        return redirect()->back();
    }

    private function show(Request $request, int $id): View
    {
        global $db, $user, $conf, $langs;

        $object = new \Fournisseur($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);

        if ($id > 0) {
            $object->fetch($id);
        }

        $data = [
            'object' => $object,
            'extrafields' => $extrafields,
            'id' => $id,
        ];

        return view('fourn.card', $data);
    }
}
