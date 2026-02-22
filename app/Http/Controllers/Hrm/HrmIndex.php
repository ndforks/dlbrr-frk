<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HrmIndex extends Controller
{
    /**
     * Handle the incoming request - HRM dashboard
     */
    public function __invoke(Request $request): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $langs->loadLangs(array('users', 'holiday', 'trips', 'boxes'));
        
        $hookmanager = new \HookManager($db);
        $hookmanager->initHooks(array('hrmindex'));
        
        $socid = GETPOSTINT("socid");
        
        if ($user->socid > 0) {
            accessforbidden();
        }
        
        if (!getDolGlobalString('MAIN_INFO_SOCIETE_NOM') || !getDolGlobalString('MAIN_INFO_SOCIETE_COUNTRY')) {
            $setupcompanynotcomplete = 1;
        } else {
            $setupcompanynotcomplete = 0;
        }
        
        $max = getDolGlobalInt('MAIN_SIZE_SHORTLIST_LIMIT', 5);
        
        if (isModEnabled('holiday') && !empty($setupcompanynotcomplete)) {
            require_once DOL_DOCUMENT_ROOT.'/holiday/class/holiday.class.php';
            $holidaystatic = new \Holiday($db);
            $result = $holidaystatic->updateBalance();
        }
        
        $childids = $user->getAllChildIds();
        $childids[] = $user->id;
        
        return view('hrm.index', [
            'langs' => $langs,
            'user' => $user,
            'conf' => $conf,
            'db' => $db,
            'setupcompanynotcomplete' => $setupcompanynotcomplete,
            'max' => $max,
            'childids' => $childids,
            'hookmanager' => $hookmanager,
        ]);
    }
}
