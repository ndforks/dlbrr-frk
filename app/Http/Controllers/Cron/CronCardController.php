<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Modules\Cron\class\Cronjob;
use Form;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CronCardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf;

        $langs->loadLangs(['admin', 'cron', 'members', 'bills']);

        $id = $request->integer('id', 0);
        $action = $request->input('action');
        $confirm = $request->input('confirm');
        $cancel = $request->input('cancel');
        $backtopage = $request->input('backtopage');
        $securitykey = $request->input('securitykey');

        $permissiontoadd = $user->hasRight('cron', 'create');
        $permissiontoexecute = $user->hasRight('cron', 'execute');
        $permissiontodelete = $user->hasRight('cron', 'delete');

        if (!$permissiontoadd) {
            abort(403);
        }

        $object = new Cronjob($db);
        if (!empty($id)) {
            $result = $object->fetch($id);
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }

        // Handle actions
        if (!empty($cancel)) {
            if (!empty($id) && empty($backtopage)) {
                $action = '';
            } else {
                return redirect($backtopage ?: route('cron.list'));
            }
        }

        // Delete
        if ($action == 'confirm_delete' && $confirm == "yes" && $permissiontodelete) {
            $result = $object->delete($user);
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                $action = 'edit';
            } else {
                return redirect()->route('cron.list');
            }
        }

        // Execute
        if ($action == 'confirm_execute' && $confirm == "yes" && $permissiontoexecute) {
            if (getDolGlobalString('CRON_KEY') && getDolGlobalString('CRON_KEY') != $securitykey) {
                setEventMessages('Security key '.$securitykey.' is wrong', null, 'errors');
            } else {
                $now = dol_now();
                $result = $object->run_jobs($user->login);
                
                if ($result < 0) {
                    setEventMessages($object->error, $object->errors, 'errors');
                } else {
                    $res = $object->reprogram_jobs($user->login, $now);
                    if ($res > 0) {
                        if ($object->lastresult > 0) {
                            setEventMessages($langs->trans("JobFinished"), null, 'warnings');
                        } else {
                            setEventMessages($langs->trans("JobFinished"), null, 'mesgs');
                        }
                    } else {
                        setEventMessages($object->error, $object->errors, 'errors');
                    }
                }
            }
            $action = '';
        }

        // Add
        if ($action == 'add') {
            $object->jobtype = $request->input('jobtype');
            $object->label = $request->input('label');
            $object->command = $request->input('command');
            $object->classesname = $request->input('classesname');
            $object->objectname = $request->input('objectname');
            $object->methodename = $request->input('methodename');
            $object->params = $request->input('params');
            $object->md5params = $request->input('md5params');
            $object->module_name = $request->input('module_name');
            $object->note_private = $request->input('note');
            $object->datestart = dol_mktime($request->integer('datestarthour', 0), $request->integer('datestartmin', 0), 0, $request->integer('datestartmonth', 0), $request->integer('datestartday', 0), $request->integer('datestartyear', 0));
            $object->dateend = dol_mktime($request->integer('dateendhour', 0), $request->integer('dateendmin', 0), 0, $request->integer('dateendmonth', 0), $request->integer('dateendday', 0), $request->integer('dateendyear', 0));
            $object->priority = $request->integer('priority', 0);
            $object->datenextrun = dol_mktime($request->integer('datenextrunhour', 0), $request->integer('datenextrunmin', 0), 0, $request->integer('datenextrunmonth', 0), $request->integer('datenextrunday', 0), $request->integer('datenextrunyear', 0));
            $object->unitfrequency = $request->input('unitfrequency');
            $object->frequency = $request->integer('nbfrequency', 0);
            $object->maxrun = $request->integer('maxrun', 0);
            $object->email_alert = $request->input('email_alert');
            $object->status = 0;
            $object->processing = 0;
            $object->lastresult = '';
            
            $result = $object->create($user);
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                $action = 'create';
            } else {
                setEventMessages($langs->trans('CronSaveSucess'), null, 'mesgs');
                $action = '';
            }
        }

        // Update
        if ($action == 'update') {
            $object->id = $id;
            $object->jobtype = $request->input('jobtype');
            $object->label = $request->input('label');
            $object->command = $request->input('command');
            $object->classesname = $request->input('classesname');
            $object->objectname = $request->input('objectname');
            $object->methodename = $request->input('methodename');
            $object->params = $request->input('params');
            $object->md5params = $request->input('md5params');
            $object->module_name = $request->input('module_name');
            $object->note_private = $request->input('note');
            $object->datestart = dol_mktime($request->integer('datestarthour', 0), $request->integer('datestartmin', 0), 0, $request->integer('datestartmonth', 0), $request->integer('datestartday', 0), $request->integer('datestartyear', 0));
            $object->dateend = dol_mktime($request->integer('dateendhour', 0), $request->integer('dateendmin', 0), 0, $request->integer('dateendmonth', 0), $request->integer('dateendday', 0), $request->integer('dateendyear', 0));
            $object->priority = $request->integer('priority', 0);
            $object->datenextrun = dol_mktime($request->integer('datenextrunhour', 0), $request->integer('datenextrunmin', 0), 0, $request->integer('datenextrunmonth', 0), $request->integer('datenextrunday', 0), $request->integer('datenextrunyear', 0));
            $object->unitfrequency = $request->input('unitfrequency');
            $object->frequency = $request->integer('nbfrequency', 0);
            $object->maxrun = $request->integer('maxrun', 0);
            $object->email_alert = $request->input('email_alert');
            
            $result = $object->update($user);
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                $action = 'edit';
            } else {
                setEventMessages($langs->trans('CronSaveSucess'), null, 'mesgs');
                $action = '';
            }
        }

        // Activate
        if ($action == 'activate') {
            $object->status = 1;
            $result = $object->update($user);
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                $action = 'edit';
            } else {
                setEventMessages($langs->trans('CronSaveSucess'), null, 'mesgs');
                $action = '';
            }
        }

        // Deactivate
        if ($action == 'inactive') {
            $object->status = 0;
            $object->processing = 0;
            $result = $object->update($user);
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
                $action = 'edit';
            } else {
                setEventMessages($langs->trans('CronSaveSucess'), null, 'mesgs');
                $action = '';
            }
        }

        // Clone
        if ($action == 'confirm_clone' && $confirm == 'yes') {
            $objectutil = dol_clone($object, 1);
            $result = $objectutil->createFromClone($user, (($object->id > 0) ? $object->id : $id));
            if (is_object($result) || $result > 0) {
                $newid = is_object($result) ? $result->id : $result;
                return redirect()->route('cron.card', ['id' => $newid]);
            } else {
                setEventMessages($objectutil->error, $objectutil->errors, 'errors');
                $action = '';
            }
        }

        return view('cron.card', [
            'object' => $object,
            'action' => $action,
            'confirm' => $confirm,
            'id' => $id,
            'securitykey' => $securitykey,
            'permissiontoadd' => $permissiontoadd,
            'permissiontoexecute' => $permissiontoexecute,
            'permissiontodelete' => $permissiontodelete,
        ]);
    }
}
