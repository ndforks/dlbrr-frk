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
     * Constructor with dependency injection
     */
    public function __construct(
        protected ModuleService $moduleService
    ) {
    }

    public function __invoke(Request $request): View|RedirectResponse
    {
        global $user;
        
        // Early return for unauthorized access
        if (!$user->admin) {
            abort(403);
        }
        
        $action = $request->input('action');
        
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
        global $user, $langs;
        
        $value = $request->input('value');
        $module = $request->input('module');
        
        // Early return for missing parameters or unauthorized access
        if (!$module || !$user->admin) {
            return redirect()->route('admin.modules');
        }
        
        $activate = (bool) $value;
        $res = $this->moduleService->setModuleStatus($module, $activate);
        
        $message = $res
            ? $langs->trans($activate ? "ModuleActivated" : "ModuleDeactivated", $module)
            : $langs->trans("ModuleNotActivated", $module);
        
        setEventMessages($message, null, $res ? 'mesgs' : 'errors');
        
        return redirect()->route('admin.modules');
    }
    
    private function resetModules(Request $request): RedirectResponse
    {
        global $user, $langs;
        
        // Early return if not confirmed or unauthorized
        if (!$user->admin || $request->input('confirm') !== 'yes') {
            return redirect()->route('admin.modules');
        }
        
        $langs->load('admin');
        
        $deletedCount = $this->moduleService->resetAllModules();
        
        setEventMessages(
            $langs->trans("ModulesReset") . " ({$deletedCount} configurations removed)",
            null,
            'mesgs'
        );
        
        return redirect()->route('admin.modules');
    }
    
    private function installModule(Request $request): RedirectResponse
    {
        global $langs;
        
        $allowonlineinstall = $this->moduleService->getConfig('MAIN_ALLOW_ONLINE_INSTALL', 0);
        
        // Early return if online install is not allowed
        if (!$allowonlineinstall) {
            setEventMessages(
                $langs->trans("InstallModuleFromWebHasBeenDisabledContactUs"),
                null,
                'errors'
            );
            return redirect()->route('admin.modules');
        }
        
        // Module installation logic would go here
        setEventMessages($langs->trans("FeatureNotYetAvailable"), null, 'warnings');
        
        return redirect()->route('admin.modules');
    }
}
