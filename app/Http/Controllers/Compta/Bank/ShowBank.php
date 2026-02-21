<?php

namespace App\Http\Controllers\Compta\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowBank extends Controller
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
            'confirm_delete' => $this->delete($request, $id),
            'close' => $this->close($request, $id),
            'reopen' => $this->reopen($request, $id),
            'create' => $this->create($request),
            'edit' => $this->edit($request, $id),
            default => $this->show($request, $id, $ref),
        };
    }
    
    private function show(Request $request, int $id, string $ref = ''): View
    {
        global $db, $langs, $user, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $langs->loadLangs(['banks', 'bills', 'categories', 'companies', 'compta', 'withdrawals']);
        $hookmanager->initHooks(['bankcard', 'globalcard']);
        
        $object = new \Account($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($id > 0 || !empty($ref)) {
            if ($id > 0) {
                $object->fetch($id);
            } elseif (!empty($ref)) {
                $object->fetch(0, $ref);
            }
        }
        
        $fieldid = $id ? 'rowid' : 'ref';
        restrictedArea($user, 'banque', $id, 'bank_account&bank_account', '', '', $fieldid);
        
        return view('bank.show', [
            'object' => $object,
            'extrafields' => $extrafields,
            'action' => 'view'
        ]);
    }
    
    private function create(Request $request): View
    {
        global $db, $langs, $user, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $langs->loadLangs(['banks', 'bills', 'categories', 'companies', 'compta', 'withdrawals']);
        $hookmanager->initHooks(['bankcard', 'globalcard']);
        restrictedArea($user, 'banque', 0, 'bank_account&bank_account');
        
        $object = new \Account($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        return view('bank.create', [
            'object' => $object,
            'extrafields' => $extrafields,
            'action' => 'create'
        ]);
    }
    
    private function edit(Request $request, int $id): View
    {
        global $db, $langs, $user, $hookmanager;
        
        if (!$user->hasRight('banque', 'configurer')) {
            return redirect("/compta/bank/card.php?id={$id}")->with('error', 'Permission denied');
        }
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $langs->loadLangs(['banks', 'bills', 'categories', 'companies', 'compta', 'withdrawals']);
        $hookmanager->initHooks(['bankcard', 'globalcard']);
        
        $object = new \Account($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($id > 0) {
            $object->fetch($id);
        }
        
        restrictedArea($user, 'banque', $id, 'bank_account&bank_account');
        
        return view('bank.edit', [
            'object' => $object,
            'extrafields' => $extrafields,
            'action' => 'edit'
        ]);
    }
    
    private function store(Request $request): RedirectResponse
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('banque', 'configurer')) {
            return redirect('/compta/bank/list.php')->with('error', 'Permission denied');
        }
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $db->begin();
        
        $object = new \Account($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $object->ref = dol_string_nospecial(trim(GETPOST('ref', 'alpha')));
        $object->label = trim(GETPOST('label', 'alphanohtml'));
        $object->type = GETPOSTINT('type');
        $object->courant = $object->type;
        $object->status = GETPOSTINT('clos');
        $object->clos = $object->status;
        $object->rappro = (GETPOST('norappro', 'alpha') ? 0 : 1);
        $object->url = trim(GETPOST('url', 'alpha'));
        
        $object->bank = trim(GETPOST('bank'));
        $object->code_banque = trim(GETPOST('code_banque'));
        $object->code_guichet = trim(GETPOST('code_guichet'));
        $object->number = trim(GETPOST('number'));
        $object->cle_rib = trim(GETPOST('cle_rib'));
        $object->bic = trim(GETPOST('bic'));
        $object->iban = trim(GETPOST('iban'));
        $object->pti_in_ctti = empty(GETPOST('pti_in_ctti')) ? 0 : 1;
        
        $object->address = trim(GETPOST('account_address', 'alphanohtml'));
        $object->owner_name = trim(GETPOST('proprio', 'alphanohtml'));
        $object->owner_address = trim(GETPOST('owner_address', 'alphanohtml'));
        $object->owner_zip = trim(GETPOST('owner_zip', 'alphanohtml'));
        $object->owner_town = trim(GETPOST('owner_town', 'alphanohtml'));
        $object->owner_country_id = GETPOSTINT('owner_country_id');
        
        $object->ics = trim(GETPOST('ics', 'alpha'));
        $object->ics_transfer = trim(GETPOST('ics_transfer', 'alpha'));
        
        $account_number = GETPOST('account_number', 'alphanohtml');
        $object->account_number = (empty($account_number) || $account_number == '-1') ? '' : $account_number;
        
        $fk_accountancy_journal = GETPOSTINT('fk_accountancy_journal');
        $object->fk_accountancy_journal = ($fk_accountancy_journal <= 0) ? 0 : $fk_accountancy_journal;
        
        $object->balance = GETPOSTFLOAT('solde');
        $object->solde = $object->balance;
        $object->date_solde = dol_mktime(12, 0, 0, GETPOSTINT('remonth'), GETPOSTINT('reday'), GETPOSTINT('reyear'));
        
        $object->currency_code = trim(GETPOST('account_currency_code'));
        $object->state_id = GETPOSTINT('account_state_id');
        $object->country_id = GETPOSTINT('account_country_id');
        $object->min_allowed = GETPOSTFLOAT('account_min_allowed');
        $object->min_desired = GETPOSTFLOAT('account_min_desired');
        $object->comment = trim(GETPOST('account_comment', 'restricthtml'));
        $object->fk_user_author = $user->id;
        
        $error = 0;
        
        if (getDolGlobalInt('MAIN_BANK_ACCOUNTANCY_CODE_ALWAYS_REQUIRED') && empty($object->account_number)) {
            setEventMessages($langs->transnoentitiesnoconv('ErrorFieldRequired', $langs->transnoentitiesnoconv('AccountancyCode')), null, 'errors');
            $error++;
        }
        if (empty($object->ref)) {
            setEventMessages($langs->transnoentitiesnoconv('ErrorFieldRequired', $langs->transnoentitiesnoconv('Ref')), null, 'errors');
            $error++;
        }
        if (empty($object->label)) {
            setEventMessages($langs->transnoentitiesnoconv('ErrorFieldRequired', $langs->transnoentitiesnoconv('LabelBankCashAccount')), null, 'errors');
            $error++;
        }
        
        $ret = $extrafields->setOptionalsFromPost(null, $object, '@GETPOSTISSET');
        
        if (!$error) {
            $id = $object->create($user);
            if ($id > 0) {
                $categories = GETPOST('categories', 'array:int');
                $object->setCategories($categories);
                $db->commit();
                
                $backtopage = GETPOST('backtopage', 'alpha');
                if (!empty($backtopage)) {
                    $backtopage = str_replace('__ID__', (string) $id, $backtopage);
                    return redirect($backtopage);
                }
                return redirect("/compta/bank/card.php?id={$id}");
            }
            
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        $db->rollback();
        return redirect('/compta/bank/card.php?action=create');
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('banque', 'configurer')) {
            return redirect("/compta/bank/card.php?id={$id}")->with('error', 'Permission denied');
        }
        
        $cancel = GETPOST('cancel', 'alpha');
        if ($cancel) {
            return redirect("/compta/bank/card.php?id={$id}");
        }
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $db->begin();
        
        $object = new \Account($db);
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($object->fetch($id)) {
            $object->ref = dol_string_nospecial(trim(GETPOST('ref', 'alpha')));
            $object->label = trim(GETPOST('label', 'alphanohtml'));
            $object->type = GETPOSTINT('type');
            $object->courant = $object->type;
            $object->status = GETPOSTINT('clos');
            $object->clos = $object->status;
            $object->rappro = (GETPOST('norappro', 'alpha') ? 0 : 1);
            $object->url = trim(GETPOST('url', 'alpha'));
            
            $object->bank = trim(GETPOST('bank'));
            $object->code_banque = trim(GETPOST('code_banque'));
            $object->code_guichet = trim(GETPOST('code_guichet'));
            $object->number = trim(GETPOST('number'));
            $object->cle_rib = trim(GETPOST('cle_rib'));
            $object->bic = trim(GETPOST('bic'));
            $object->iban = trim(GETPOST('iban'));
            $object->pti_in_ctti = empty(GETPOST('pti_in_ctti')) ? 0 : 1;
            
            $object->address = trim(GETPOST('account_address', 'alphanohtml'));
            $object->owner_name = trim(GETPOST('proprio', 'alphanohtml'));
            $object->owner_address = trim(GETPOST('owner_address', 'alphanohtml'));
            $object->owner_zip = trim(GETPOST('owner_zip', 'alphanohtml'));
            $object->owner_town = trim(GETPOST('owner_town', 'alphanohtml'));
            $object->owner_country_id = GETPOSTINT('owner_country_id');
            
            $object->ics = trim(GETPOST('ics', 'alpha'));
            $object->ics_transfer = trim(GETPOST('ics_transfer', 'alpha'));
            
            $account_number = GETPOST('account_number', 'alphanohtml');
            $object->account_number = (empty($account_number) || $account_number == '-1') ? '' : $account_number;
            
            $fk_accountancy_journal = GETPOSTINT('fk_accountancy_journal');
            $object->fk_accountancy_journal = ($fk_accountancy_journal <= 0) ? 0 : $fk_accountancy_journal;
            
            $object->currency_code = trim(GETPOST('account_currency_code'));
            $object->state_id = GETPOSTINT('account_state_id');
            $object->country_id = GETPOSTINT('account_country_id');
            $object->min_allowed = GETPOSTFLOAT('account_min_allowed');
            $object->min_desired = GETPOSTFLOAT('account_min_desired');
            $object->comment = trim(GETPOST('account_comment', 'restricthtml'));
            
            $ret = $extrafields->setOptionalsFromPost(null, $object, '@GETPOSTISSET');
            
            $result = $object->update($user);
            if ($result > 0) {
                $categories = GETPOST('categories', 'array:int');
                $object->setCategories($categories);
                $db->commit();
                setEventMessages($langs->trans('RecordSaved'), null, 'mesgs');
                return redirect("/compta/bank/card.php?id={$id}");
            }
            
            setEventMessages($object->error, $object->errors, 'errors');
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        $db->rollback();
        return redirect("/compta/bank/card.php?action=edit&id={$id}");
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('banque', 'configurer')) {
            return redirect("/compta/bank/card.php?id={$id}")->with('error', 'Permission denied');
        }
        
        $confirm = GETPOST('confirm');
        if ($confirm !== 'yes') {
            return redirect("/compta/bank/card.php?id={$id}");
        }
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        
        $object = new \Account($db);
        $object->fetch($id);
        $result = $object->delete($user);
        
        if ($result > 0) {
            setEventMessages($langs->trans('RecordDeleted'), null, 'mesgs');
            return redirect('/compta/bank/list.php?restore_lastsearch_values=1');
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect("/compta/bank/card.php?id={$id}");
    }
    
    private function close(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        if (!$user->hasRight('banque', 'configurer')) {
            return redirect("/compta/bank/card.php?id={$id}")->with('error', 'Permission denied');
        }
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        
        $object = new \Account($db);
        $object->fetch($id);
        $object->setStatut($object::STATUS_CLOSED, null, '', 'BANKACCOUNT_MODIFY');
        
        return redirect("/compta/bank/card.php?id={$id}");
    }
    
    private function reopen(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        if (!$user->hasRight('banque', 'configurer')) {
            return redirect("/compta/bank/card.php?id={$id}")->with('error', 'Permission denied');
        }
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        
        $object = new \Account($db);
        $object->fetch($id);
        $object->setStatut($object::STATUS_OPEN, null, '', 'BANKACCOUNT_MODIFY');
        
        return redirect("/compta/bank/card.php?id={$id}");
    }
}
