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
        
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        $ref = $request->input('ref');
        $socid = $request->integer('socid', 0);
        
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
        
        if ($request->method() === 'POST' && $request->input('add')) {
            $object = new SupplierProposal($db);
            $object->ref = $request->input('ref');
            $object->socid = $request->integer('socid', 0);
            $object->date = dol_mktime(0, 0, 0, $request->integer('remonth', 0), $request->integer('reday', 0), $request->integer('reyear', 0));
            $object->cond_reglement_id = $request->integer('cond_reglement_id', 0);
            $object->mode_reglement_id = $request->integer('mode_reglement_id', 0);
            $object->fk_project = $request->integer('projectid', 0);
            
            $result = $object->create($user);
            if ($result > 0) {
                return redirect()->route('supplier_proposal.show', ['id' => $object->id]);
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
        
        $object->ref = $request->input('ref');
        $object->socid = $request->integer('socid', 0);
        $object->date = dol_mktime(0, 0, 0, $request->integer('remonth', 0), $request->integer('reday', 0), $request->integer('reyear', 0));
        
        $result = $object->update($user);
        if ($result > 0) {
            return redirect()->route('supplier_proposal.show', ['id' => $id]);
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect()->route('supplier_proposal.show', ['id' => $id, 'action' => 'edit']);
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $confirm = $request->input('confirm');
        if ($confirm === 'yes') {
            $object = new SupplierProposal($db);
            $object->fetch($id);
            
            $result = $object->delete($user);
            if ($result > 0) {
                return redirect()->route('supplier_proposal.list');
            }
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect()->route('supplier_proposal.show', ['id' => $id]);
    }
    
    private function validate(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $confirm = $request->input('confirm');
        if ($confirm === 'yes') {
            $object = new SupplierProposal($db);
            $object->fetch($id);
            
            $result = $object->valid($user);
            if ($result >= 0) {
                return redirect()->route('supplier_proposal.show', ['id' => $id]);
            }
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect()->route('supplier_proposal.show', ['id' => $id]);
    }
    
    private function close(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $object = new SupplierProposal($db);
        $object->fetch($id);
        
        $result = $object->cloture($user, $request->integer('statut', 0), $request->input('note'));
        if ($result >= 0) {
            return redirect()->route('supplier_proposal.show', ['id' => $id]);
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect()->route('supplier_proposal.show', ['id' => $id]);
    }
    
    private function setDraft(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $object = new SupplierProposal($db);
        $object->fetch($id);
        
        $result = $object->setDraft($user);
        if ($result >= 0) {
            return redirect()->route('supplier_proposal.show', ['id' => $id]);
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect()->route('supplier_proposal.show', ['id' => $id]);
    }
}
