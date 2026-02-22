<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminIndex extends Controller
{
    public function __invoke(): View
    {
        global $conf, $user, $langs, $mysoc, $hookmanager;
        
        $langs->loadLangs(['admin', 'companies']);
        
        if (!$user->admin) {
            accessforbidden();
        }
        
        $hookmanager->initHooks(['homesetup']);
        
        $setupcompanynotcomplete = 0;
        if (!getDolGlobalString('MAIN_INFO_SOCIETE_NOM') || 
            !getDolGlobalString('MAIN_INFO_SOCIETE_COUNTRY') || 
            getDolGlobalString('MAIN_INFO_SOCIETE_SETUP_TODO_WARNING')) {
            $setupcompanynotcomplete = 1;
        }
        
        $nbmodulesnotautoenabled = count($conf->modules);
        $listofmodulesautoenabled = ['user', 'agenda', 'fckeditor', 'export', 'import'];
        foreach ($listofmodulesautoenabled as $moduleautoenable) {
            if (in_array($moduleautoenable, $conf->modules)) {
                $nbmodulesnotautoenabled--;
            }
        }
        
        return view('admin.index', [
            'setupcompanynotcomplete' => $setupcompanynotcomplete,
            'nbmodulesnotautoenabled' => $nbmodulesnotautoenabled,
            'mysoc' => $mysoc,
            'conf' => $conf,
            'langs' => $langs,
            'hookmanager' => $hookmanager,
        ]);
    }
}
