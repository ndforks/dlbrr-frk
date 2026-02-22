<?php

namespace App\Http\Controllers\Hrm;
use App\Modules\Core\Classes\ExtraFields;
use App\Modules\Hrm\Classes\Job;
use App\Modules\Hrm\Classes\Position;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionHrm extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $action = GETPOST('action', 'aZ09') ?: 'view';
        $id = GETPOSTINT('id');
        $ref = GETPOST('ref', 'alpha');
        $confirm = GETPOST('confirm', 'alpha');
        $cancel = GETPOST('cancel', 'alpha');
        $fk_job = GETPOSTISSET('fk_job') ? GETPOSTINT('fk_job') : $id;
        $fk_user = GETPOSTINT('fk_user');
        
        require_once DOL_DOCUMENT_ROOT.'/hrm/class/position.class.php';        require_once DOL_DOCUMENT_ROOT.'/hrm/lib/hrm_position.lib.php';
        require_once DOL_DOCUMENT_ROOT.'/hrm/lib/hrm_job.lib.php';
        
        $langs->loadLangs(array("hrm", "other", 'products'));
        
        if (!isModEnabled('hrm')) {
            accessforbidden();
        }
        
        $permissiontoread = $user->hasRight('hrm', 'all', 'read');
        $permissiontoadd = $user->hasRight('hrm', 'all', 'write');
        $permissiontodelete = $user->hasRight('hrm', 'all', 'delete');
        
        if (!$permissiontoread || ($action === 'create' && !$permissiontoadd)) {
            accessforbidden();
        }
        
        return match($action) {
            'create', 'add' => $this->create($request, $fk_job),
            'edit' => $this->edit($request, $id, $ref),
            'update' => $this->update($request, $id),
            'delete' => $this->handleDelete($request, $id, $confirm),
            'confirm_delete' => $this->delete($request, $id),
            default => $this->show($request, $id, $ref, $fk_job),
        };
    }
    
    private function create(Request $request, int $fk_job): View
    {
        global $db, $langs, $user, $conf;
        
        $permissiontoadd = $user->hasRight('hrm', 'all', 'write');
        
        if (!$permissiontoadd) {
            accessforbidden('NotEnoughPermissions', 0, 1);
        }
        
        $object = new Position($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $form = new \Form($db);
        $backtopage = GETPOST('backtopage', 'alpha');
        $backtopageforcancel = GETPOST('backtopageforcancel', 'alpha');
        
        return view('hrm.position_create', [
            'object' => $object,
            'extrafields' => $extrafields,
            'form' => $form,
            'langs' => $langs,
            'fk_job' => $fk_job,
            'backtopage' => $backtopage,
            'backtopageforcancel' => $backtopageforcancel,
            'permissiontoadd' => $permissiontoadd,
        ]);
    }
    
    private function edit(Request $request, int $id, string $ref): View
    {
        global $db, $langs, $user, $conf;
        
        $permissiontoadd = $user->hasRight('hrm', 'all', 'write');
        
        $object = new Position($db);
        
        if ($id > 0 || !empty($ref)) {
            $result = $object->fetch($id, $ref);
            if ($result <= 0) {
                accessforbidden('ErrorRecordNotFound');
            }
        }
        
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $form = new \Form($db);
        $backtopage = GETPOST('backtopage', 'alpha');
        $backtopageforcancel = GETPOST('backtopageforcancel', 'alpha');
        
        return view('hrm.position_edit', [
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
        
        $object = new Position($db);
        $object->fetch($id);
        
        foreach ($object->fields as $key => $val) {
            if (isset($_POST[$key])) {
                if ($key === 'fk_job') {
                    $object->$key = GETPOSTINT($key);
                } elseif ($key === 'fk_user') {
                    $object->$key = GETPOSTINT($key);
                } else {
                    $object->$key = GETPOST($key, isset($val['type']) ? $val['type'] : 'alpha');
                }
            }
        }
        
        $result = $object->update($user);
        
        if ($result > 0) {
            setEventMessages($langs->trans('RecordSaved'), null, 'mesgs');
            return redirect("/hrm/position.php?id=".$object->fk_job);
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect("/hrm/position.php?id=".$id."&action=edit");
        }
    }
    
    private function handleDelete(Request $request, int $id, string $confirm): View|RedirectResponse
    {
        if ($confirm === 'yes') {
            return $this->delete($request, $id);
        }
        
        return $this->show($request, $id, '', 0);
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $db, $user, $langs;
        
        $permissiontodelete = $user->hasRight('hrm', 'all', 'delete');
        
        if (!$permissiontodelete) {
            accessforbidden();
        }
        
        $object = new Position($db);
        $object->fetch($id);
        
        $result = $object->delete($user);
        
        if ($result > 0) {
            setEventMessages($langs->trans('RecordDeleted'), null, 'mesgs');
            return redirect("/hrm/position_list.php");
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect("/hrm/position.php?id=".$id);
        }
    }
    
    private function show(Request $request, int $id, string $ref, int $fk_job): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $permissiontoread = $user->hasRight('hrm', 'all', 'read');
        $permissiontoadd = $user->hasRight('hrm', 'all', 'write');
        $permissiontodelete = $user->hasRight('hrm', 'all', 'delete');
        
        if (!$permissiontoread) {
            accessforbidden();
        }
        
        $job = new Job($db);
        $objectposition = new Position($db);
        $extrafields = new ExtraFields($db);
        
        if ($fk_job > 0) {
            $job->fetch($fk_job);
        } elseif ($id > 0) {
            $job->fetch($id);
        }
        
        $extrafields->fetch_name_optionals_label($objectposition->table_element);
        
        $form = new \Form($db);
        $formfile = new \FormFile($db);
        
        if ($job->id > 0) {
            $head = jobPrepareHead($job);
        } else {
            $head = [];
        }
        
        $limit = GETPOSTINT('limit') ? GETPOSTINT('limit') : $conf->liste_limit;
        $sortfield = GETPOST('sortfield', 'aZ09comma');
        $sortorder = GETPOST('sortorder', 'aZ09comma');
        $page = GETPOSTISSET('pageplusone') ? (GETPOSTINT('pageplusone') - 1) : GETPOSTINT("page");
        if (empty($page) || $page < 0 || GETPOST('button_search', 'alpha') || GETPOST('button_removefilter', 'alpha')) {
            $page = 0;
        }
        $offset = $limit * $page;
        
        if (!$sortfield) {
            reset($objectposition->fields);
            $sortfield = "t.".key($objectposition->fields);
        }
        if (!$sortorder) {
            $sortorder = "ASC";
        }
        
        return view('hrm.position_card', [
            'job' => $job,
            'objectposition' => $objectposition,
            'extrafields' => $extrafields,
            'form' => $form,
            'formfile' => $formfile,
            'langs' => $langs,
            'user' => $user,
            'conf' => $conf,
            'db' => $db,
            'head' => $head,
            'fk_job' => $fk_job,
            'limit' => $limit,
            'sortfield' => $sortfield,
            'sortorder' => $sortorder,
            'page' => $page,
            'offset' => $offset,
            'permissiontoread' => $permissiontoread,
            'permissiontoadd' => $permissiontoadd,
            'permissiontodelete' => $permissiontodelete,
            'action' => GETPOST('action', 'aZ09'),
        ]);
    }
}

