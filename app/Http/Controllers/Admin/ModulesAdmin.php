<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ModuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModulesAdmin extends Controller
{
    /**
     * Module service instance
     */
    protected ModuleService $moduleService;

    /**
     * Constructor
     */
    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function __invoke(Request $request): View|RedirectResponse
    {
        global $conf, $user, $langs, $db;
        
        $action = $request->input('action');
        
        if (!$user->admin) {
            abort(403);
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
        
        $search_keyword = $request->input('search_keyword');
        $search_status = $request->input('search_status');
        $search_nature = $request->input('search_nature');
        $search_version = $request->input('search_version');
        
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
        global $conf, $user, $langs;
        
        $value = $request->input('value');
        $module = $request->input('module');
        
        if ($module && $user->admin) {
            $activate = (bool) $value;
            $res = $this->moduleService->setModuleStatus($module, $activate);
            
            if ($res) {
                $message = $activate 
                    ? $langs->trans("ModuleActivated", $module) 
                    : $langs->trans("ModuleDeactivated", $module);
                setEventMessages($message, null, 'mesgs');
            } else {
                setEventMessages($langs->trans("ModuleNotActivated", $module), null, 'errors');
            }
        }
        
        return redirect()->route('admin.modules');
    }
    
    private function resetModules(Request $request): RedirectResponse
    {
        global $conf, $user, $langs;
        
        if ($user->admin && $request->input('confirm') == 'yes') {
            $langs->load('admin');
            
            // Use service to reset all modules via Eloquent
            $deletedCount = $this->moduleService->resetAllModules();
            
            setEventMessages($langs->trans("ModulesReset") . " ({$deletedCount} configurations removed)", null, 'mesgs');
        }
        
        return redirect()->route('admin.modules');
    }
    
    private function installModule(Request $request): RedirectResponse
    {
        global $conf, $langs;
        
        $allowonlineinstall = $this->moduleService->getConfig('MAIN_ALLOW_ONLINE_INSTALL', 0);
        
        if (!$allowonlineinstall) {
            setEventMessages($langs->trans("InstallModuleFromWebHasBeenDisabledContactUs"), null, 'errors');
            return redirect()->route('admin.modules');
        }
        
        // Module installation logic would go here
        setEventMessages($langs->trans("FeatureNotYetAvailable"), null, 'warnings');
        
        return redirect()->route('admin.modules');
    }
}
