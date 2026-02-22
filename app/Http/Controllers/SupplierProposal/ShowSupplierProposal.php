<?php

namespace App\Http\Controllers\SupplierProposal;
use App\Modules\SupplierProposal\Classes\SupplierProposal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowSupplierProposal extends Controller
{
    /**
     * Handle the incoming request.
     * Displays, edits, creates, or updates a supplier proposal.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $conf, $db, $langs, $user, $hookmanager, $mysoc;
        
        require_once DOL_DOCUMENT_ROOT.'/supplier_proposal/class/supplier_proposal.class.php';        require_once DOL_DOCUMENT_ROOT.'/core/lib/supplier_proposal.lib.php';
        
        $langs->loadLangs(['companies', 'supplier_proposal', 'compta', 'bills', 'propal', 'orders', 'products']);
        
        if (!isModEnabled('supplier_proposal')) {
            accessforbidden('Module not enabled');
        }
        
        $hookmanager->initHooks(['supplier_proposalcard', 'globalcard']);
        
        $action = GETPOST('action', 'aZ09') ?: 'view';
        $id = GETPOSTINT('id');
        $ref = GETPOST('ref', 'alpha');
        $socid = GETPOSTINT('socid');
        
        if (!empty($user->socid)) {
            $socid = $user->socid;
        }
        
        return match($action) {
            'create', 'add' => $this->create($request, $socid),
            'edit' => $this->edit($request, $id, $ref),
            'update' => $this->update($request, $id),
            'confirm_delete' => $this->delete($request, $id),
            'confirm_validate' => $this->validate($request, $id),
            'close' => $this->close($request, $id),
            'setdraft' => $this->setDraft($request, $id),
            default => $this->show($request, $id, $ref),
        };
    }
    
    private function show(Request $request, int $id, ?string $ref = null): View
    {
        global $db, $user;
        
        $object = new SupplierProposal($db);
        if ($id > 0 || !empty($ref)) {
            $result = $object->fetch($id, $ref);
            if ($result > 0) {
                $object->fetch_thirdparty();
            }
        }
        
        restrictedArea($user, 'supplier_proposal', $object->id);
        
        $usercanread = $user->hasRight('supplier_proposal', 'lire');
        $usercancreate = $user->hasRight('supplier_proposal', 'creer');
        $usercandelete = $user->hasRight('supplier_proposal', 'supprimer');
        $usercanvalidate = ((!getDolGlobalString('MAIN_USE_ADVANCED_PERMS') && !empty($usercancreate)) || (getDolGlobalString('MAIN_USE_ADVANCED_PERMS') && $user->hasRight('supplier_proposal', 'validate_advance')));
        $usercanclose = $user->hasRight('supplier_proposal', 'cloturer');
        
        return view('supplier_proposal.show', [
            'object' => $object,
            'usercanread' => $usercanread,
            'usercancreate' => $usercancreate,
            'usercandelete' => $usercandelete,
            'usercanvalidate' => $usercanvalidate,
            'usercanclose' => $usercanclose,
        ]);
    }
    
    private function create(Request $request, ?int $socid = null): View|RedirectResponse
    {
        global $db, $user, $langs;
        
        $usercancreate = $user->hasRight('supplier_proposal', 'creer');
        if (!$usercancreate) {
            accessforbidden();
        }
        
        if ($request->method() === 'POST' && GETPOST('add')) {
            $object = new SupplierProposal($db);
            $object->ref = GETPOST('ref', 'alpha');
            $object->socid = GETPOSTINT('socid');
            $object->date = dol_mktime(0, 0, 0, GETPOSTINT('remonth'), GETPOSTINT('reday'), GETPOSTINT('reyear'));
            $object->cond_reglement_id = GETPOSTINT('cond_reglement_id');
            $object->mode_reglement_id = GETPOSTINT('mode_reglement_id');
            $object->fk_project = GETPOSTINT('projectid');
            
            $result = $object->create($user);
            if ($result > 0) {
                return redirect("/supplier_proposal/card.php?id={$object->id}");
            } else {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }
        
        return view('supplier_proposal.create', ['socid' => $socid]);
    }
    
    private function edit(Request $request, int $id, ?string $ref = null): View
    {
        global $db, $user;
        
        $object = new SupplierProposal($db);
        $object->fetch($id, $ref);
        $object->fetch_thirdparty();
        
        restrictedArea($user, 'supplier_proposal', $object->id);
        
        return view('supplier_proposal.edit', ['object' => $object]);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $object = new SupplierProposal($db);
        $object->fetch($id);
        
        $object->ref = GETPOST('ref', 'alpha');
        $object->socid = GETPOSTINT('socid');
        $object->date = dol_mktime(0, 0, 0, GETPOSTINT('remonth'), GETPOSTINT('reday'), GETPOSTINT('reyear'));
        
        $result = $object->update($user);
        if ($result > 0) {
            return redirect("/supplier_proposal/card.php?id={$id}");
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect("/supplier_proposal/card.php?id={$id}&action=edit");
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $confirm = GETPOST('confirm', 'alpha');
        if ($confirm === 'yes') {
            $object = new SupplierProposal($db);
            $object->fetch($id);
            
            $result = $object->delete($user);
            if ($result > 0) {
                return redirect('/supplier_proposal/list.php');
            }
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/supplier_proposal/card.php?id={$id}");
    }
    
    private function validate(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $confirm = GETPOST('confirm', 'alpha');
        if ($confirm === 'yes') {
            $object = new SupplierProposal($db);
            $object->fetch($id);
            
            $result = $object->valid($user);
            if ($result >= 0) {
                return redirect("/supplier_proposal/card.php?id={$id}");
            }
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/supplier_proposal/card.php?id={$id}");
    }
    
    private function close(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $object = new SupplierProposal($db);
        $object->fetch($id);
        
        $result = $object->cloture($user, GETPOSTINT('statut'), GETPOST('note', 'restricthtml'));
        if ($result >= 0) {
            return redirect("/supplier_proposal/card.php?id={$id}");
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect("/supplier_proposal/card.php?id={$id}");
    }
    
    private function setDraft(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $object = new SupplierProposal($db);
        $object->fetch($id);
        
        $result = $object->setDraft($user);
        if ($result >= 0) {
            return redirect("/supplier_proposal/card.php?id={$id}");
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect("/supplier_proposal/card.php?id={$id}");
    }
}
