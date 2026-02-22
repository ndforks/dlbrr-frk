<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModulesAdmin extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $conf, $user, $langs, $db;
        
        $action = GETPOST('action', 'aZ09');
        
        if (!$user->admin) {
            accessforbidden();
        }
        
        return match($action) {
            'set' => $this->setModule($request),
            'reset' => $this->resetModules($request),
            'install' => $this->installModule($request),
            default => $this->show($request),
        };
    }
    
    private function show(Request $request): View
    {
        global $conf, $user, $langs, $db, $hookmanager;
        
        $langs->loadLangs(['errors', 'admin', 'modulebuilder']);
        
        $search_keyword = GETPOST('search_keyword', 'alpha');
        $search_status = GETPOST('search_status', 'alpha');
        $search_nature = GETPOST('search_nature', 'alpha');
        $search_version = GETPOST('search_version', 'alpha');
        
        return view('admin.modules', [
            'search_keyword' => $search_keyword,
            'search_status' => $search_status,
            'search_nature' => $search_nature,
            'search_version' => $search_version,
            'conf' => $conf,
            'langs' => $langs,
            'db' => $db,
            'hookmanager' => $hookmanager,
        ]);
    }
    
    private function setModule(Request $request): RedirectResponse
    {
        global $db, $conf, $user, $langs;
        
        $value = GETPOST('value', 'alpha');
        $module = GETPOST('module', 'alpha');
        
        if ($module && $user->admin) {
            $res = activateModule($module, $value);
            if ($res) {
                setEventMessages($langs->trans("ModuleActivated", $module), null, 'mesgs');
            } else {
                setEventMessages($langs->trans("ModuleNotActivated", $module), null, 'errors');
            }
        }
        
        return redirect('/admin/modules.php');
    }
    
    private function resetModules(Request $request): RedirectResponse
    {
        global $db, $conf, $user, $langs;
        
        if ($user->admin && GETPOST('confirm') == 'yes') {
            $langs->load('admin');
            
            $sql = "DELETE FROM ".MAIN_DB_PREFIX."const WHERE name LIKE '%_MODULE_%'";
            $db->query($sql);
            
            setEventMessages($langs->trans("ModulesReset"), null, 'mesgs');
        }
        
        return redirect('/admin/modules.php');
    }
    
    private function installModule(Request $request): RedirectResponse
    {
        global $conf, $langs, $db;
        
        $allowonlineinstall = getDolGlobalInt('MAIN_ALLOW_ONLINE_INSTALL');
        
        if (!$allowonlineinstall) {
            setEventMessages($langs->trans("InstallModuleFromWebHasBeenDisabledContactUs"), null, 'errors');
            return redirect('/admin/modules.php');
        }
        
        // Module installation logic would go here
        setEventMessages($langs->trans("FeatureNotYetAvailable"), null, 'warnings');
        
        return redirect('/admin/modules.php');
    }
}
