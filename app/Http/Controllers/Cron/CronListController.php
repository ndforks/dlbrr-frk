<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Modules\Cron\class\Cronjob;
use ExtraFields;
use Form;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CronListController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf, $hookmanager;

        $langs->loadLangs(['admin', 'cron', 'bills', 'members']);

        $action = $request->input('action');
        $massaction = $request->input('massaction', []);
        $confirm = $request->input('confirm');
        $toselect = $request->input('toselect', []);
        $contextpage = $request->input('contextpage', 'cronjoblist');
        $optioncss = $request->input('optioncss');
        $mode = $request->input('mode');

        // Search criteria
        $search_status = $request->input('search_status');
        $search_label = $request->input('search_label');
        $search_module_name = $request->input('search_module_name');
        $search_lastresult = $request->input('search_lastresult');
        $search_processing = $request->input('search_processing');
        $securitykey = $request->input('securitykey');
        $id = $request->integer('id', 0);

        // Pagination
        $limit = $request->integer('limit', 0) ?: $conf->liste_limit;
        $sortfield = $request->input('sortfield', 't.priority,t.status');
        $sortorder = $request->input('sortorder', 'ASC,DESC');
        $page = $request->has('pageplusone') ? ($request->integer('pageplusone', 0) - 1) : $request->integer('page', 0);
        
        if (empty($page) || $page < 0 || $request->input('button_search') || $request->input('button_removefilter')) {
            $page = 0;
        }
        $offset = $limit * $page;

        $object = new Cronjob($db);
        $extrafields = new ExtraFields($db);
        $hookmanager->initHooks(['cronjoblist']);

        $extrafields->fetch_name_optionals_label($object->table_element);
        $search_array_options = $extrafields->getOptionalsFromPost($object->table_element, '', 'search_');

        $permissiontoread = $user->hasRight('cron', 'read');
        $permissiontoadd = $user->hasRight('cron', 'create');
        $permissiontodelete = $user->hasRight('cron', 'delete');
        $permissiontoexecute = $user->hasRight('cron', 'execute');

        if (!$permissiontoread) {
            abort(403);
        }

        // Handle cancel
        if ($request->input('cancel')) {
            $action = 'list';
            $massaction = '';
        }

        // Clear filters
        if ($request->input('button_removefilter_x') || $request->input('button_removefilter.x') || $request->input('button_removefilter')) {
            $search_label = '';
            $search_status = -1;
            $search_lastresult = '';
            $search_module_name = '';
            $toselect = [];
            $search_array_options = [];
        }

        if ($request->input('button_removefilter_x') || $request->input('button_removefilter.x') || $request->input('button_removefilter')
            || $request->input('button_search_x') || $request->input('button_search.x') || $request->input('button_search')) {
            $massaction = '';
        }

        // Delete job
        if ($action == 'confirm_delete' && $confirm == "yes" && $permissiontodelete) {
            $object = new Cronjob($db);
            $object->id = $id;
            $result = $object->delete($user);
            if ($result < 0) {
                setEventMessages($object->error, $object->errors, 'errors');
            }
        }

        // Execute job
        if ($action == 'confirm_execute' && $confirm == "yes" && $permissiontoexecute) {
            if (getDolGlobalString('CRON_KEY') && getDolGlobalString('CRON_KEY') != $securitykey) {
                setEventMessages('Security key '.$securitykey.' is wrong', null, 'errors');
                $action = '';
            } else {
                $object = new Cronjob($db);
                $job = $object->fetch($id);
                $now = dol_now();

                $resrunjob = $object->run_jobs($user->login);
                if ($resrunjob < 0) {
                    setEventMessages($object->error, $object->errors, 'errors');
                }

                $res = $object->reprogram_jobs($user->login, $now);
                if ($res > 0) {
                    if ($resrunjob >= 0) {
                        if ($object->lastresult >= 0) {
                            setEventMessages($langs->trans("JobFinished"), null, 'mesgs');
                        } else {
                            setEventMessages($langs->trans("JobFinished"), null, 'errors');
                        }
                    }
                    $action = '';
                } else {
                    setEventMessages($object->error, $object->errors, 'errors');
                    $action = '';
                }

                $param = '&search_status='.urlencode($search_status);
                if (!empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"]) {
                    $param .= '&contextpage='.urlencode($contextpage);
                }
                if ($limit > 0 && $limit != $conf->liste_limit) {
                    $param .= '&limit='.((int) $limit);
                }
                if ($search_label) {
                    $param .= '&search_label='.urlencode($search_label);
                }
                if ($optioncss != '') {
                    $param .= '&optioncss='.urlencode($optioncss);
                }

                return redirect()->route('cron.list', array_filter([
                    'sortfield' => $sortfield,
                    'sortorder' => $sortorder,
                    'search_status' => $search_status,
                    'search_label' => $search_label,
                ]));
            }
        }

        // Mass actions
        if ($massaction && $permissiontoadd) {
            $tmpcron = new Cronjob($db);
            foreach ($toselect as $id) {
                $result = $tmpcron->fetch($id);
                if ($result) {
                    $result = 0;
                    if ($massaction == 'disable') {
                        $result = $tmpcron->setStatut(Cronjob::STATUS_DISABLED);
                    } elseif ($massaction == 'enable') {
                        $result = $tmpcron->setStatut(Cronjob::STATUS_ENABLED);
                    }
                    if ($result < 0) {
                        setEventMessages($tmpcron->error, $tmpcron->errors, 'errors');
                    }
                }
            }
        }

        return view('cron.list', [
            'action' => $action,
            'confirm' => $confirm,
            'search_status' => $search_status,
            'search_label' => $search_label,
            'search_module_name' => $search_module_name,
            'search_lastresult' => $search_lastresult,
            'search_processing' => $search_processing,
            'sortfield' => $sortfield,
            'sortorder' => $sortorder,
            'page' => $page,
            'limit' => $limit,
            'contextpage' => $contextpage,
            'optioncss' => $optioncss,
            'mode' => $mode,
            'securitykey' => $securitykey,
            'permissiontoadd' => $permissiontoadd,
            'permissiontodelete' => $permissiontodelete,
            'permissiontoexecute' => $permissiontoexecute,
        ]);
    }
}
