<?php

namespace App\Http\Controllers\Ecm;
use App\Modules\Ecm\Classes\EcmDirectory;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class EcmIndex extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $conf, $db, $langs, $user, $hookmanager;
        
        // Load translation files
        $langs->loadLangs(array('ecm', 'companies', 'other', 'users', 'orders', 'propal', 'bills', 'contracts'));
        
        // Get parameters
        $action = $request->input('action');
        $section = $request->integer('section', 0) ?: $request->integer('section_id', 0) ?: 0;
        $section_dir = $request->input('section_dir');
        $overwritefile = $request->integer('overwritefile', 0);
        
        // Security check
        $result = restrictedArea($user, 'ecm', 0);
        
        $permissiontoread = $user->hasRight('ecm', 'read');
        $permissiontocreate = $user->hasRight('ecm', 'upload');
        $permissiontocreatedir = $user->hasRight('ecm', 'setup');
        $permissiontodelete = $user->hasRight('ecm', 'upload');
        $permissiontodeletedir = $user->hasRight('ecm', 'setup');
        
        // Initialize hooks
        $hookmanager->initHooks(array('ecmindexcard', 'globalcard'));
        
        // Handle file upload
        if ($request->input("sendit") && getDolGlobalString('MAIN_UPLOAD_DOC') && $user->hasRight('ecm', 'upload')) {
            return $this->uploadFile($request);
        }
        
        return match($action) {
            'add' => $this->add($request),
            'confirm_deletefile' => $this->deleteFile($request),
            'confirm_deletesection' => $this->deleteSection($request),
            'refreshmanual' => $this->refreshManual($request),
            default => $this->show($request),
        };
    }
    
    private function uploadFile(Request $request): RedirectResponse
    {
        global $conf, $db, $langs, $user;        $section = $request->integer('section', 0) ?: $request->integer('section_id', 0) ?: 0;
        $section_dir = $request->input('section_dir');
        $overwritefile = $request->integer('overwritefile', 0);
        
        $ecmdir = new EcmDirectory($db);
        
        // Define relativepath and upload_dir
        if ($section > 0) {
            $ecmdir->fetch($section);
            $relativepath = $ecmdir->getRelativePath();
        } else {
            $relativepath = $section_dir;
        }
        $upload_dir = $conf->ecm->dir_output.'/'.$relativepath;
        
        $error = 0;
        $userfiles = [];
        if (is_array($_FILES['userfile'])) {
            if (is_array($_FILES['userfile']['tmp_name'])) {
                $userfiles = $_FILES['userfile']['tmp_name'];
            } else {
                $userfiles = array($_FILES['userfile']['tmp_name']);
            }
        }
        
        foreach ($userfiles as $key => $userfile) {
            if (empty($_FILES['userfile']['tmp_name'][$key])) {
                $error++;
                if ($_FILES['userfile']['error'][$key] == 1 || $_FILES['userfile']['error'][$key] == 2) {
                    setEventMessages($langs->trans('ErrorFileSizeTooLarge'), null, 'errors');
                } else {
                    setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("File")), null, 'errors');
                }
            }
        }
        
        if (!$error) {
            $generatethumbs = 0;
            $res = dol_add_file_process($upload_dir, $overwritefile, 1, 'userfile', '', null, '', $generatethumbs);
            if ($res > 0) {
                $result = $ecmdir->changeNbOfFiles('+');
            }
        }
        
        return redirect($_SERVER["PHP_SELF"]);
    }
    
    private function show(Request $request): View
    {
        global $conf, $db, $langs, $user;
        
        require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/lib/ecm.lib.php';
        require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
        require_once DOL_DOCUMENT_ROOT.'/core/lib/treeview.lib.php';        $section = $request->integer('section', 0) ?: $request->integer('section_id', 0) ?: 0;
        
        $ecmdir = new EcmDirectory($db);
        if ($section > 0) {
            $result = $ecmdir->fetch($section);
            if (!($result > 0)) {
                dol_print_error($db, $ecmdir->error);
                exit;
            }
        }
        
        // Define height of file area
        $maxheightwin = (isset($_SESSION["dol_screenheight"]) && $_SESSION["dol_screenheight"] > 466) ? ($_SESSION["dol_screenheight"] - 136) : 660;
        
        $morejs = array();
        if (!getDolGlobalString('MAIN_ECM_DISABLE_JS')) {
            $morejs[] = "public/includes/jquery/plugins/jqueryFileTree/jqueryFileTree.js";
        }
        
        // Capture legacy output
        ob_start();
        
        llxHeader('', $langs->trans("ECMArea"), '', '', 0, 0, $morejs, '', '', 'mod-ecm page-index');
        
        $head = ecm_prepare_dasboard_head();
        print dol_get_fiche_head($head, 'index', '', -1, '');
        
        // Add filemanager component
        $module = 'ecm';
        $url = DOL_URL_ROOT.'/ecm/index.php';
        include DOL_DOCUMENT_ROOT.'/core/tpl/filemanager.tpl.php';
        
        print dol_get_fiche_end();
        llxFooter();
        
        $output = ob_get_clean();
        
        return response($output);
    }
    
    private function add(Request $request): RedirectResponse
    {
        global $db, $user, $langs;        if (!$user->hasRight('ecm', 'setup')) {
            accessforbidden();
        }
        
        $ecmdir = new EcmDirectory($db);
        $ecmdir->ref = 'NOTUSEDYET';
        $ecmdir->label = $request->input("label");
        $ecmdir->description = $request->input("desc");
        
        $id = $ecmdir->create($user);
        if ($id > 0) {
            return redirect($_SERVER["PHP_SELF"]);
        } else {
            setEventMessages('Error '.$langs->trans($ecmdir->error), null, 'errors');
            return redirect()->back();
        }
    }
    
    private function deleteFile(Request $request): RedirectResponse
    {
        global $conf, $db, $user, $langs;        if (!$user->hasRight('ecm', 'upload')) {
            accessforbidden();
        }
        
        if ($request->input('confirm') == 'yes') {
            $section = $request->integer('section', 0) ?: $request->integer('section_id', 0) ?: 0;
            $section_dir = $request->input('section_dir');
            
            $relativepath = '';
            $ecmdir = new EcmDirectory($db);
            
            if ($section > 0) {
                $ecmdir->fetch($section);
                $relativepath = $ecmdir->getRelativePath();
            } else {
                $relativepath = $section_dir;
            }
            
            $upload_dir = $conf->ecm->dir_output.($relativepath ? '/'.$relativepath : '');
            $file = $upload_dir."/".$request->input('urlfile');
            $ret = dol_delete_file($file);
            
            if ($ret) {
                $urlfiletoshow = $request->input('urlfile');
                $urlfiletoshow = preg_replace('/\.noexe$/', '', $urlfiletoshow);
                setEventMessages($langs->trans("FileWasRemoved", $urlfiletoshow), null, 'mesgs');
                $ecmdir->changeNbOfFiles('-');
            } else {
                setEventMessages($langs->trans("ErrorFailToDeleteFile", $request->input('urlfile')), null, 'errors');
            }
            
            clearstatcache();
        }
        
        return redirect($_SERVER["PHP_SELF"]);
    }
    
    private function deleteSection(Request $request): RedirectResponse
    {
        global $db, $user, $langs;        if (!$user->hasRight('ecm', 'setup')) {
            accessforbidden();
        }
        
        if ($request->input('confirm') == 'yes') {
            $section = $request->integer('section', 0) ?: $request->integer('section_id', 0) ?: 0;
            
            $ecmdir = new EcmDirectory($db);
            $ecmdir->fetch($section);
            $result = $ecmdir->delete($user);
            
            setEventMessages($langs->trans("ECMSectionWasRemoved", $ecmdir->label), null, 'mesgs');
            clearstatcache();
        }
        
        return redirect($_SERVER["PHP_SELF"]);
    }
    
    private function refreshManual(Request $request): RedirectResponse
    {
        global $conf, $db, $user;        if (!$user->hasRight('ecm', 'read')) {
            accessforbidden();
        }
        
        $ecmdirstatic = new EcmDirectory($db);
        $ecmdirtmp = new EcmDirectory($db);
        
        clearstatcache();
        
        $diroutputslash = str_replace('\\', '/', $conf->ecm->dir_output);
        $diroutputslash .= '/';
        
        // Scan directory tree on disk
        $disktree = dol_dir_list($conf->ecm->dir_output, 'directories', 1, '', '^temp$', '', 0, 0);
        
        // Scan directory tree in database
        $sqltree = $ecmdirstatic->get_full_arbo(0);
        
        $adirwascreated = 0;
        
        // Compare both trees to complete missing trees into database
        foreach ($disktree as $dirdesc) {
            $dirisindatabase = 0;
            foreach ($sqltree as $dirsqldesc) {
                if ($conf->ecm->dir_output.'/'.$dirsqldesc['fullrelativename'] == $dirdesc['fullname']) {
                    $dirisindatabase = 1;
                    break;
                }
            }
            
            if (!$dirisindatabase) {
                $fk_parent = -1;
                $relativepathmissing = str_replace($diroutputslash, '', $dirdesc['fullname']);
                $relativepathtosearchparent = $relativepathmissing;
                
                if (preg_match('/\//', $relativepathtosearchparent)) {
                    $relativepathtosearchparent = preg_replace('/\/[^\/]*$/', '', $relativepathtosearchparent);
                    $parentdirisindatabase = 0;
                    foreach ($sqltree as $dirsqldesc) {
                        if ($dirsqldesc['fullrelativename'] == $relativepathtosearchparent) {
                            $parentdirisindatabase = $dirsqldesc['id'];
                            break;
                        }
                    }
                    if ($parentdirisindatabase > 0) {
                        $fk_parent = $parentdirisindatabase;
                    }
                } else {
                    $fk_parent = 0;
                }
                
                if ($fk_parent >= 0) {
                    $ecmdirtmp->ref = 'NOTUSEDYET';
                    $ecmdirtmp->label = dol_basename($dirdesc['fullname']);
                    $ecmdirtmp->description = '';
                    $ecmdirtmp->fk_parent = $fk_parent;
                    
                    $id = $ecmdirtmp->create($user);
                    if ($id > 0) {
                        $newdirsql = array('id' => $id,
                                         'id_mere' => $ecmdirtmp->fk_parent,
                                         'label' => $ecmdirtmp->label,
                                         'description' => $ecmdirtmp->description,
                                         'fullrelativename' => $relativepathmissing);
                        $sqltree[] = $newdirsql;
                        $adirwascreated = 1;
                    }
                }
            }
        }
        
        // Loop on each sql tree to check if dir exists
        foreach ($sqltree as $dirdesc) {
            $dirtotest = $conf->ecm->dir_output.'/'.$dirdesc['fullrelativename'];
            if (!dol_is_dir($dirtotest)) {
                $ecmdirtmp->id = $dirdesc['id'];
                $ecmdirtmp->delete($user, 'databaseonly');
            }
        }
        
        $sql = "UPDATE ".MAIN_DB_PREFIX."ecm_directories set cachenbofdoc = -1 WHERE cachenbofdoc < 0";
        $db->query($sql);
        
        if ($adirwascreated) {
            $sqltree = null;
        }
        
        return redirect($_SERVER["PHP_SELF"]);
    }
}
