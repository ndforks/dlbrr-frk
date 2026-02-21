<?php

namespace App\Http\Controllers\Variants;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowVariants extends Controller
{
    /**
     * Handle the incoming request.
     * Displays, edits, creates, or updates a product attribute.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $conf, $db, $langs, $user, $hookmanager, $mysoc;
        
        require_once DOL_DOCUMENT_ROOT.'/variants/class/ProductAttribute.class.php';
        require_once DOL_DOCUMENT_ROOT.'/variants/class/ProductAttributeValue.class.php';
        require_once DOL_DOCUMENT_ROOT.'/variants/lib/variants.lib.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        
        $langs->loadLangs(['products']);
        
        if (!isModEnabled('variants')) {
            accessforbidden('Module not enabled');
        }
        if ($user->socid > 0) {
            accessforbidden();
        }
        
        $hookmanager->initHooks(['productattributecard', 'globalcard']);
        restrictedArea($user, 'variants');
        
        $action = GETPOST('action', 'aZ09') ?: 'view';
        $id = GETPOSTINT('id');
        
        return match($action) {
            'create', 'add' => $this->create($request),
            'edit' => $this->edit($request, $id),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            'addline' => $this->addLine($request, $id),
            'updateline' => $this->updateLine($request, $id),
            'up' => $this->moveLineUp($request, $id),
            'down' => $this->moveLineDown($request, $id),
            default => $this->show($request, $id),
        };
    }
    
    private function show(Request $request, int $id): View
    {
        global $db, $user;
        
        $object = new \ProductAttribute($db);
        $object->fetch($id);
        
        $permissiontoread = $user->hasRight('variants', 'read');
        $permissiontoedit = $user->hasRight('variants', 'write');
        $permissiontodelete = $user->hasRight('variants', 'delete');
        
        return view('variants.show', [
            'object' => $object,
            'permissiontoread' => $permissiontoread,
            'permissiontoedit' => $permissiontoedit,
            'permissiontodelete' => $permissiontodelete,
        ]);
    }
    
    private function create(Request $request): View|RedirectResponse
    {
        global $db, $user, $langs;
        
        if ($request->method() === 'POST' && GETPOST('add')) {
            $object = new \ProductAttribute($db);
            $object->ref = GETPOST('ref', 'alpha');
            $object->label = GETPOST('label', 'alpha');
            
            $result = $object->create($user);
            if ($result > 0) {
                return redirect("/variants/card.php?id={$object->id}");
            } else {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }
        
        return view('variants.create');
    }
    
    private function edit(Request $request, int $id): View
    {
        global $db;
        
        $object = new \ProductAttribute($db);
        $object->fetch($id);
        
        return view('variants.edit', ['object' => $object]);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $object = new \ProductAttribute($db);
        $object->fetch($id);
        
        $object->ref = GETPOST('ref', 'alpha');
        $object->label = GETPOST('label', 'alpha');
        
        $result = $object->update($user);
        if ($result > 0) {
            return redirect("/variants/card.php?id={$id}");
        }
        
        setEventMessages($object->error, $object->errors, 'errors');
        return redirect("/variants/card.php?id={$id}&action=edit");
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $confirm = GETPOST('confirm', 'alpha');
        if ($confirm === 'yes') {
            $object = new \ProductAttribute($db);
            $object->fetch($id);
            $result = $object->delete($user);
            
            if ($result > 0) {
                return redirect('/variants/list.php');
            }
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/variants/card.php?id={$id}");
    }
    
    private function addLine(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $object = new \ProductAttribute($db);
        $object->fetch($id);
        
        $line_ref = GETPOST('line_ref', 'alpha');
        $line_value = GETPOST('line_value', 'alpha');
        
        $result = $object->addLine($line_ref, $line_value);
        if ($result > 0) {
            setEventMessages($langs->trans('RecordSaved'), null, 'mesgs');
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/variants/card.php?id={$id}");
    }
    
    private function updateLine(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $object = new \ProductAttribute($db);
        $object->fetch($id);
        
        $lineid = GETPOSTINT('lineid');
        $line_ref = GETPOST('line_ref', 'alpha');
        $line_value = GETPOST('line_value', 'alpha');
        
        $result = $object->updateLine($lineid, $line_ref, $line_value);
        if ($result > 0) {
            setEventMessages($langs->trans('RecordSaved'), null, 'mesgs');
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/variants/card.php?id={$id}");
    }
    
    private function moveLineUp(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $object = new \ProductAttribute($db);
        $object->fetch($id);
        
        $rowid = GETPOSTINT('rowid');
        $object->line_up($rowid, false);
        
        return redirect("/variants/card.php?id={$id}#{$rowid}");
    }
    
    private function moveLineDown(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $object = new \ProductAttribute($db);
        $object->fetch($id);
        
        $rowid = GETPOSTINT('rowid');
        $object->line_down($rowid, false);
        
        return redirect("/variants/card.php?id={$id}#{$rowid}");
    }
}
