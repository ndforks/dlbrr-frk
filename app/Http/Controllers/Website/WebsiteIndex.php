<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Modules\Website\class\Website;
use App\Modules\Website\class\WebsitePage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;

class WebsiteIndex extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse|Response
    {
        global $conf, $db, $user, $langs, $dolibarr_main_data_root, $dolibarr_main_url_root;

        // Security check
        if (!$user->hasRight('website', 'read')) {
            accessforbidden();
        }

        $conf->dol_hide_leftmenu = 1;

        // Get parameters
        $action = GETPOST('action', 'aZ09') ?: 'preview';
        $websiteid = GETPOSTINT('websiteid');
        $websitekey = GETPOST('website', 'alpha');
        $pageid = GETPOSTINT('pageid');
        $pageref = GETPOST('pageref', 'alphanohtml');
        $confirm = GETPOST('confirm', 'alpha');

        // Handle file_manager and other modes
        $file_manager = GETPOST('file_manager', 'alpha');
        $replacesite = GETPOST('replacesite', 'alpha');
        
        if ($file_manager && empty($action)) {
            $action = 'file_manager';
        }
        if ($action == 'replacesite' || (empty($action) && $replacesite)) {
            $mode = 'replacesite';
        }

        // Override action based on POST parameters
        if (GETPOST('deletesite', 'alpha')) $action = 'deletesite';
        if (GETPOST('delete', 'alpha')) $action = 'delete';
        if (GETPOST('preview', 'alpha')) $action = 'preview';
        if (GETPOST('createsite', 'alpha')) $action = 'createsite';
        if (GETPOST('createcontainer', 'alpha')) $action = 'createcontainer';
        if (GETPOST('editcss', 'alpha')) $action = 'editcss';
        if (GETPOST('editmenu', 'alpha')) $action = 'editmenu';
        if (GETPOST('setashome', 'alpha')) $action = 'setashome';
        if (GETPOST('editmeta', 'alpha')) $action = 'editmeta';
        if (GETPOST('editsource', 'alpha')) $action = 'editsource';
        if (GETPOST('editcontent', 'alpha')) $action = 'editcontent';
        if (GETPOST('exportsite', 'alpha')) $action = 'exportsite';
        if (GETPOST('importsite')) $action = 'importsite';
        if (GETPOST('createfromclone', 'alpha')) $action = 'createfromclone';
        if (GETPOST('createpagefromclone', 'alpha')) $action = 'createpagefromclone';

        // Load objects
        $object = new Website($db);
        $objectpage = new WebsitePage($db);
        
        $listofwebsites = $object->fetchAll('ASC', 'position');
        
        // Auto-select first website if none selected
        if (!($websiteid > 0) && empty($websitekey) && $action != 'createsite') {
            foreach ($listofwebsites as $key => $valwebsite) {
                $websitekey = $valwebsite->ref;
                break;
            }
        }
        
        if ($websiteid > 0 || $websitekey) {
            $object->fetch($websiteid, $websitekey);
            $websitekey = $object->ref;
        }

        // Load page if pageid or pageref provided
        if (($pageid > 0 || $pageref) && $action != 'addcontainer') {
            $res = $objectpage->fetch($pageid, ($object->id > 0 ? $object->id : null), $pageref);
            if ($res == 0) {
                $res = $objectpage->fetch($pageid, ($object->id > 0 ? $object->id : null), null, $pageref);
            }
            
            // Validate page belongs to website
            if ($res >= 0 && $object->id > 0) {
                if ($objectpage->fk_website != $object->id) {
                    if ($object->fk_default_home > 0) {
                        $objectpage->fetch($object->fk_default_home, (string) $object->id, '');
                        $pageid = $object->fk_default_home;
                    } else {
                        $res = $objectpage->fetch(0, (string) $object->id, '');
                        if ($res > 0) {
                            $pageid = $objectpage->id;
                        }
                    }
                } else {
                    $pageid = $objectpage->id;
                }
            }
        }

        // Set default page if none selected
        if (empty($pageid) && empty($pageref) && $object->id > 0 && $action != 'createcontainer') {
            $pageid = $object->fk_default_home;
            if (empty($pageid)) {
                $array = $objectpage->fetchAll($object->id, 'ASC,ASC', 'type_container,pageurl');
                if (is_array($array) && count($array) > 0) {
                    $firstpageid = 0;
                    foreach ($array as $key => $valpage) {
                        if (empty($firstpageid)) {
                            $firstpageid = $valpage->id;
                        }
                        if ($object->fk_default_home && $key == $object->fk_default_home) {
                            $pageid = $valpage->id;
                            break;
                        }
                    }
                    if (empty($pageid)) $pageid = $firstpageid;
                }
            }
        }

        $usercanedit = $user->hasRight('website', 'write');
        $permissiontodelete = $user->hasRight('website', 'delete');

        // Route to appropriate action handler
        return match($action) {
            'createsite', 'addsite' => $this->createSite($request, $object, $usercanedit),
            'createcontainer', 'addcontainer' => $this->createContainer($request, $object, $objectpage, $usercanedit),
            'deletesite', 'confirm_deletesite' => $this->deleteSite($request, $object, $confirm, $permissiontodelete),
            'delete' => $this->deletePage($request, $objectpage, $confirm, $permissiontodelete),
            'updatecss' => $this->updateCss($request, $object, $usercanedit),
            'updatesecurity' => $this->updateSecurity($request, $object, $usercanedit),
            'file_manager' => $this->fileManager($request, $object, $websitekey),
            'exportsite' => $this->exportSite($request, $object),
            'importsite' => $this->importSite($request, $object, $usercanedit),
            'setwebsiteonline' => $this->setWebsiteOnline($request, $object, $usercanedit),
            'setwebsiteoffline' => $this->setWebsiteOffline($request, $object, $usercanedit),
            default => $this->show($request, $object, $objectpage, $pageid, $listofwebsites),
        };
    }

    private function show(Request $request, $object, $objectpage, $pageid, $listofwebsites): Response
    {
        // For complex CMS interface, delegate to legacy file for now
        // This is the main preview/edit interface
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function createSite(Request $request, $object, $usercanedit): RedirectResponse|Response
    {
        if (!$usercanedit) {
            accessforbidden();
        }
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function createContainer(Request $request, $object, $objectpage, $usercanedit): RedirectResponse|Response
    {
        if (!$usercanedit) {
            accessforbidden();
        }
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function deleteSite(Request $request, $object, $confirm, $permissiontodelete): RedirectResponse|Response
    {
        if (!$permissiontodelete) {
            accessforbidden();
        }
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function deletePage(Request $request, $objectpage, $confirm, $permissiontodelete): RedirectResponse|Response
    {
        if (!$permissiontodelete) {
            accessforbidden();
        }
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function updateCss(Request $request, $object, $usercanedit): RedirectResponse|Response
    {
        if (!$usercanedit) {
            accessforbidden();
        }
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function updateSecurity(Request $request, $object, $usercanedit): RedirectResponse|Response
    {
        if (!$usercanedit) {
            accessforbidden();
        }
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function fileManager(Request $request, $object, $websitekey): Response
    {
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function exportSite(Request $request, $object): Response
    {
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function importSite(Request $request, $object, $usercanedit): RedirectResponse|Response
    {
        if (!$usercanedit) {
            accessforbidden();
        }
        return $this->executeDolibarrFile('Website/index.php');
    }

    private function setWebsiteOnline(Request $request, $object, $usercanedit): RedirectResponse
    {
        if (!$usercanedit) {
            accessforbidden();
        }
        
        global $db;
        $object->setStatut(1);
        
        return redirect()->back()->with('success', 'Website set online');
    }

    private function setWebsiteOffline(Request $request, $object, $usercanedit): RedirectResponse
    {
        if (!$usercanedit) {
            accessforbidden();
        }
        
        global $db;
        $object->setStatut(0);
        
        return redirect()->back()->with('success', 'Website set offline');
    }

    private function executeDolibarrFile(string $file): Response
    {
        $filePath = app_path('Modules/' . $file);
        
        if (!file_exists($filePath)) {
            abort(404, "Legacy file not found: {$file}");
        }

        ob_start();
        require $filePath;
        $content = ob_get_clean();

        return response($content);
    }
}
