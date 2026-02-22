<?php

namespace App\Http\Controllers\Compta\Bank;
use App\Modules\Core\Classes\ExtraFields;
use App\Modules\Compta\Bank\Classes\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowBank extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        $ref = $request->input('ref', '');
        
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
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';        $langs->loadLangs(['banks', 'bills', 'categories', 'companies', 'compta', 'withdrawals']);
        $hookmanager->initHooks(['bankcard', 'globalcard']);
        
        $object = new Account($db);
        $extrafields = new ExtraFields($db);
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
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';        $langs->loadLangs(['banks', 'bills', 'categories', 'companies', 'compta', 'withdrawals']);
        $hookmanager->initHooks(['bankcard', 'globalcard']);
        restrictedArea($user, 'banque', 0, 'bank_account&bank_account');
        
        $object = new Account($db);
        $extrafields = new ExtraFields($db);
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
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';        $langs->loadLangs(['banks', 'bills', 'categories', 'companies', 'compta', 'withdrawals']);
        $hookmanager->initHooks(['bankcard', 'globalcard']);
        
        $object = new Account($db);
        $extrafields = new ExtraFields($db);
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
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';        $db->begin();
        
        $object = new Account($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $object->ref = dol_string_nospecial(trim($request->input('ref')));
        $object->label = trim($request->input('label'));
        $object->type = $request->integer('type', 0);
        $object->courant = $object->type;
        $object->status = $request->integer('clos', 0);
        $object->clos = $object->status;
        $object->rappro = ($request->input('norappro') ? 0 : 1);
        $object->url = trim($request->input('url'));
        
        $object->bank = trim($request->input('bank'));
        $object->code_banque = trim($request->input('code_banque'));
        $object->code_guichet = trim($request->input('code_guichet'));
        $object->number = trim($request->input('number'));
        $object->cle_rib = trim($request->input('cle_rib'));
        $object->bic = trim($request->input('bic'));
        $object->iban = trim($request->input('iban'));
        $object->pti_in_ctti = empty($request->input('pti_in_ctti')) ? 0 : 1;
        
        $object->address = trim($request->input('account_address'));
        $object->owner_name = trim($request->input('proprio'));
        $object->owner_address = trim($request->input('owner_address'));
        $object->owner_zip = trim($request->input('owner_zip'));
        $object->owner_town = trim($request->input('owner_town'));
        $object->owner_country_id = $request->integer('owner_country_id', 0);
        
        $object->ics = trim($request->input('ics'));
        $object->ics_transfer = trim($request->input('ics_transfer'));
        
        $account_number = $request->integer('account_number', 0);
        $object->account_number = (empty($account_number) || $account_number == '-1') ? '' : $account_number;
        
        $fk_accountancy_journal = $request->integer('fk_accountancy_journal', 0);
        $object->fk_accountancy_journal = ($fk_accountancy_journal <= 0) ? 0 : $fk_accountancy_journal;
        
        $object->balance = $request->input('solde', 0.0);
        $object->solde = $object->balance;
        $object->date_solde = dol_mktime(12, 0, 0, $request->integer('remonth', 0), $request->integer('reday', 0), $request->integer('reyear', 0));
        
        $object->currency_code = trim($request->input('account_currency_code'));
        $object->state_id = $request->integer('account_state_id', 0);
        $object->country_id = $request->integer('account_country_id', 0);
        $object->min_allowed = $request->input('account_min_allowed', 0.0);
        $object->min_desired = $request->input('account_min_desired', 0.0);
        $object->comment = trim($request->input('account_comment'));
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
        
        $ret = $extrafields->setOptionsFromPost($request, $object);
        
        if (!$error) {
            $id = $object->create($user);
            if ($id > 0) {
                $categories = $request->input('categories');
                $object->setCategories($categories);
                $db->commit();
                
                $backtopage = $request->input('backtopage');
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
        
        $cancel = $request->input('cancel');
        if ($cancel) {
            return redirect("/compta/bank/card.php?id={$id}");
        }
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';        $db->begin();
        
        $object = new Account($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($object->fetch($id)) {
            $object->ref = dol_string_nospecial(trim($request->input('ref')));
            $object->label = trim($request->input('label'));
            $object->type = $request->integer('type', 0);
            $object->courant = $object->type;
            $object->status = $request->integer('clos', 0);
            $object->clos = $object->status;
            $object->rappro = ($request->input('norappro') ? 0 : 1);
            $object->url = trim($request->input('url'));
            
            $object->bank = trim($request->input('bank'));
            $object->code_banque = trim($request->input('code_banque'));
            $object->code_guichet = trim($request->input('code_guichet'));
            $object->number = trim($request->input('number'));
            $object->cle_rib = trim($request->input('cle_rib'));
            $object->bic = trim($request->input('bic'));
            $object->iban = trim($request->input('iban'));
            $object->pti_in_ctti = empty($request->input('pti_in_ctti')) ? 0 : 1;
            
            $object->address = trim($request->input('account_address'));
            $object->owner_name = trim($request->input('proprio'));
            $object->owner_address = trim($request->input('owner_address'));
            $object->owner_zip = trim($request->input('owner_zip'));
            $object->owner_town = trim($request->input('owner_town'));
            $object->owner_country_id = $request->integer('owner_country_id', 0);
            
            $object->ics = trim($request->input('ics'));
            $object->ics_transfer = trim($request->input('ics_transfer'));
            
            $account_number = $request->integer('account_number', 0);
            $object->account_number = (empty($account_number) || $account_number == '-1') ? '' : $account_number;
            
            $fk_accountancy_journal = $request->integer('fk_accountancy_journal', 0);
            $object->fk_accountancy_journal = ($fk_accountancy_journal <= 0) ? 0 : $fk_accountancy_journal;
            
            $object->currency_code = trim($request->input('account_currency_code'));
            $object->state_id = $request->integer('account_state_id', 0);
            $object->country_id = $request->integer('account_country_id', 0);
            $object->min_allowed = $request->input('account_min_allowed', 0.0);
            $object->min_desired = $request->input('account_min_desired', 0.0);
            $object->comment = trim($request->input('account_comment'));
            
            $ret = $extrafields->setOptionsFromPost($request, $object);
            
            $result = $object->update($user);
            if ($result > 0) {
                $categories = $request->input('categories');
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
        
        $confirm = $request->input('confirm');
        if ($confirm !== 'yes') {
            return redirect("/compta/bank/card.php?id={$id}");
        }        $object = new Account($db);
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
        }        $object = new Account($db);
        $object->fetch($id);
        $object->setStatut($object::STATUS_CLOSED, null, '', 'BANKACCOUNT_MODIFY');
        
        return redirect("/compta/bank/card.php?id={$id}");
    }
    
    private function reopen(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        if (!$user->hasRight('banque', 'configurer')) {
            return redirect("/compta/bank/card.php?id={$id}")->with('error', 'Permission denied');
        }        $object = new Account($db);
        $object->fetch($id);
        $object->setStatut($object::STATUS_OPEN, null, '', 'BANKACCOUNT_MODIFY');
        
        return redirect("/compta/bank/card.php?id={$id}");
    }
}
