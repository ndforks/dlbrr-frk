<?php

namespace App\Http\Controllers\Bookcal;
use App\Modules\Core\Classes\ExtraFields;
use App\Modules\Bookcal\Classes\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarBookcal extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf, $hookmanager, $mysoc;
        
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        $ref = $request->input('ref');
        $confirm = $request->input('confirm');
        $cancel = $request->input('cancel');        require_once DOL_DOCUMENT_ROOT.'/bookcal/lib/bookcal_calendar.lib.php';
        
        $langs->loadLangs(array("agenda", "other"));
        
        if (!isModEnabled("bookcal")) {
            accessforbidden();
        }
        
        return match($action) {
            'create', 'add' => $this->create($request),
            'edit' => $this->edit($request, $id, $ref),
            'update' => $this->update($request, $id),
            'delete' => $this->handleDelete($request, $id, $confirm),
            'confirm_delete' => $this->delete($request, $id),
            'confirm_validate' => $this->validate($request, $id),
            'confirm_setdraft' => $this->setDraft($request, $id),
            'confirm_clone' => $this->clone($request, $id),
            'set_thirdparty' => $this->setThirdparty($request, $id),
            'classin' => $this->setProject($request, $id),
            default => $this->show($request, $id, $ref),
        };
    }
    
    private function create(Request $request): View
    {
        global $db, $langs, $user, $conf, $mysoc;
        
        $permissiontoadd = $user->hasRight('bookcal', 'calendar', 'write');
        
        if (empty($permissiontoadd)) {
            accessforbidden('NotEnoughPermissions', 0, 1);
        }
        
        $object = new Calendar($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $form = new \Form($db);
        $backtopage = $request->input('backtopage');
        $backtopageforcancel = $request->input('backtopageforcancel');
        $dol_openinpopup = $request->input('dol_openinpopup');
        
        return view('bookcal.calendar_create', [
            'object' => $object,
            'extrafields' => $extrafields,
            'form' => $form,
            'langs' => $langs,
            'backtopage' => $backtopage,
            'backtopageforcancel' => $backtopageforcancel,
            'dol_openinpopup' => $dol_openinpopup,
        ]);
    }
    
    private function edit(Request $request, int $id, string $ref): View
    {
        global $db, $langs, $user, $conf;
        
        $permissiontoadd = $user->hasRight('bookcal', 'calendar', 'write');
        
        $object = new Calendar($db);
        
        if ($id > 0 || !empty($ref)) {
            $result = $object->fetch($id, $ref);
            if ($result <= 0) {
                accessforbidden('ErrorRecordNotFound');
            }
        }
        
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $form = new \Form($db);
        $backtopage = $request->input('backtopage');
        $backtopageforcancel = $request->input('backtopageforcancel');
        
        return view('bookcal.calendar_edit', [
            'object' => $object,
            'extrafields' => $extrafields,
            'form' => $form,
            'langs' => $langs,
            'backtopage' => $backtopage,
            'backtopageforcancel' => $backtopageforcancel,
            'permissiontoadd' => $permissiontoadd,
        ]);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $object = new Calendar($db);
        $object->fetch($id);
        
        foreach ($object->fields as $key => $val) {
            if (isset($_POST[$key])) {
                $object->$key = $request->input($key);
            }
        }
        
        $result = $object->update($user);
        
        if ($result > 0) {
            setEventMessages($langs->trans('RecordSaved'), null, 'mesgs');
            return redirect("/bookcal/calendar_card.php?id=".$object->id);
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect("/bookcal/calendar_card.php?id=".$id."&action=edit");
        }
    }
    
    private function handleDelete(Request $request, int $id, string $confirm): View|RedirectResponse
    {
        if ($confirm === 'yes') {
            return $this->delete($request, $id);
        }
        
        return $this->show($request, $id, '');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $permissiontodelete = $user->hasRight('bookcal', 'calendar', 'delete');
        
        if (!$permissiontodelete) {
            accessforbidden();
        }
        
        $object = new Calendar($db);
        $object->fetch($id);
        
        $result = $object->delete($user);
        
        if ($result > 0) {
            setEventMessages($langs->trans('RecordDeleted'), null, 'mesgs');
            return redirect("/bookcal/calendar_list.php");
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect("/bookcal/calendar_card.php?id=".$id);
        }
    }
    
    private function validate(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $object = new Calendar($db);
        $object->fetch($id);
        
        $result = $object->validate($user);
        
        if ($result >= 0) {
            setEventMessages($langs->trans('RecordValidated'), null, 'mesgs');
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/bookcal/calendar_card.php?id=".$id);
    }
    
    private function setDraft(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $object = new Calendar($db);
        $object->fetch($id);
        
        $result = $object->setDraft($user);
        
        if ($result >= 0) {
            setEventMessages($langs->trans('RecordSetToDraft'), null, 'mesgs');
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        return redirect("/bookcal/calendar_card.php?id=".$id);
    }
    
    private function clone(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $object = new Calendar($db);
        $object->fetch($id);
        
        $result = $object->createFromClone($user, $id);
        
        if ($result > 0) {
            setEventMessages($langs->trans('RecordCloned'), null, 'mesgs');
            return redirect("/bookcal/calendar_card.php?id=".$result);
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect("/bookcal/calendar_card.php?id=".$id);
        }
    }
    
    private function setThirdparty(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $permissiontoadd = $user->hasRight('bookcal', 'calendar', 'write');
        
        if (!$permissiontoadd) {
            accessforbidden();
        }
        
        $object = new Calendar($db);
        $object->fetch($id);
        
        $object->setValueFrom('fk_soc', $request->integer('fk_soc', 0), '', null, 'date', '', $user, 'BOOKCAL_MYOBJECT_MODIFY');
        
        return redirect("/bookcal/calendar_card.php?id=".$id);
    }
    
    private function setProject(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        $permissiontoadd = $user->hasRight('bookcal', 'calendar', 'write');
        
        if (!$permissiontoadd) {
            accessforbidden();
        }
        
        $object = new Calendar($db);
        $object->fetch($id);
        
        $object->setProject($request->integer('projectid', 0));
        
        return redirect("/bookcal/calendar_card.php?id=".$id);
    }
    
    private function show(Request $request, int $id, string $ref): View
    {
        global $db, $langs, $user, $conf, $hookmanager, $mysoc, $dolibarr_main_url_root;
        
        $permissiontoread = $user->hasRight('bookcal', 'calendar', 'read');
        $permissiontoadd = $user->hasRight('bookcal', 'calendar', 'write');
        $permissiontodelete = $user->hasRight('bookcal', 'calendar', 'delete');
        
        if (!$permissiontoread) {
            accessforbidden();
        }
        
        $object = new Calendar($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        if ($id > 0 || !empty($ref)) {
            $result = $object->fetch($id, $ref);
            if ($result <= 0) {
                accessforbidden('ErrorRecordNotFound');
            }
        }
        
        $form = new \Form($db);
        $formfile = new \FormFile($db);
        $formproject = new \FormProjets($db);
        
        $head = calendarPrepareHead($object);
        
        $urlwithouturlroot = preg_replace('/'.preg_quote(DOL_URL_ROOT, '/').'$/i', '', trim($dolibarr_main_url_root));
        $urlwithroot = $urlwithouturlroot.DOL_URL_ROOT;
        
        $linktobook = $urlwithroot.'/public/bookcal/index.php?id='.$object->id;
        $encodedsecurekey = dol_hash(getDolGlobalString('BOOKCAL_SECUREKEY').'bookcal'.((int) $object->id), 'md5');
        $linktobook .= '&securekey='.urlencode($encodedsecurekey);
        
        return view('bookcal.calendar_card', [
            'object' => $object,
            'extrafields' => $extrafields,
            'form' => $form,
            'formfile' => $formfile,
            'formproject' => $formproject,
            'langs' => $langs,
            'user' => $user,
            'conf' => $conf,
            'head' => $head,
            'linktobook' => $linktobook,
            'permissiontoread' => $permissiontoread,
            'permissiontoadd' => $permissiontoadd,
            'permissiontodelete' => $permissiontodelete,
            'action' => $request->input('action'),
        ]);
    }
}
