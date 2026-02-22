<?php

namespace App\Http\Controllers\Ecm;
use App\Modules\Ecm\Classes\EcmDirectory;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AutoIndexEcm extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $conf, $db, $langs, $user, $hookmanager;
        
        // Load translation files
        $langs->loadLangs(array("ecm", "companies", "other", "users", "orders", "propal", "bills", "contracts"));
        
        // Get parameters
        $action = GETPOST('action', 'aZ09');
        $section = GETPOSTINT('section') ?: GETPOSTINT('section_id') ?: 0;
        $module = GETPOST('module', 'alpha');
        
        // Security check
        $result = restrictedArea($user, 'ecm', 0);
        
        // Initialize hooks
        $hookmanager->initHooks(array('ecmautocard', 'globalcard'));
        
        return match($action) {
            'add' => $this->add($request),
            'confirm_deletefile' => $this->deleteFile($request),
            'confirm_deletesection' => $this->deleteSection($request),
            'refreshmanual' => $this->refreshManual($request),
            default => $this->show($request),
        };
    }
    
    private function show(Request $request): View
    {
        global $conf, $db, $langs, $user, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/lib/ecm.lib.php';
        require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
        require_once DOL_DOCUMENT_ROOT.'/core/lib/treeview.lib.php';        $section = GETPOSTINT('section') ?: GETPOSTINT('section_id') ?: 0;
        $module = GETPOST('module', 'alpha');
        $action = GETPOST('action', 'aZ09');
        
        $ecmdir = new EcmDirectory($db);
        if ($section) {
            $result = $ecmdir->fetch($section);
            if (!($result > 0)) {
                dol_print_error($db, $ecmdir->error);
                exit;
            }
        }
        
        $form = new \Form($db);
        
        // Define height of file area
        $maxheightwin = (isset($_SESSION["dol_screenheight"]) && $_SESSION["dol_screenheight"] > 466) ? ($_SESSION["dol_screenheight"] - 136) : 660;
        
        $morejs = array();
        if (!getDolGlobalString('MAIN_ECM_DISABLE_JS')) {
            $morejs[] = "public/includes/jquery/plugins/jqueryFileTree/jqueryFileTree.js";
        }
        
        llxHeader('', $langs->trans("ECMArea"), '', '', 0, 0, $morejs, '', '', 'mod-ecm page-index_auto');
        
        // Build section auto array
        $rowspan = 0;
        $sectionauto = array();
        if (!getDolGlobalString('ECM_AUTO_TREE_HIDEN')) {
            if (isModEnabled("product") || isModEnabled("service")) {
                $langs->load("products");
                $rowspan++;
                $sectionauto[] = array('position' => 10, 'level' => 1, 'module' => 'product', 'test' => $user->hasRight('produit', 'lire'), 'label' => $langs->trans("ProductsAndServices"), 'desc' => $langs->trans("ECMDocsByProducts"));
            }
            if (isModEnabled("societe")) {
                $rowspan++;
                $sectionauto[] = array('position' => 20, 'level' => 1, 'module' => 'company', 'test' => $user->hasRight('societe', 'lire'), 'label' => $langs->trans("ThirdParties"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("ThirdParties")));
            }
            if (isModEnabled("propal")) {
                $rowspan++;
                $sectionauto[] = array('position' => 30, 'level' => 1, 'module' => 'propal', 'test' => $user->hasRight('propale', 'lire'), 'label' => $langs->trans("Proposals"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Proposals")));
            }
            if (isModEnabled('contract')) {
                $rowspan++;
                $sectionauto[] = array('position' => 40, 'level' => 1, 'module' => 'contract', 'test' => $user->hasRight('contrat', 'lire'), 'label' => $langs->trans("Contracts"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Contracts")));
            }
            if (isModEnabled('order')) {
                $rowspan++;
                $sectionauto[] = array('position' => 50, 'level' => 1, 'module' => 'order', 'test' => $user->hasRight('commande', 'lire'), 'label' => $langs->trans("CustomersOrders"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Orders")));
            }
            if (isModEnabled('invoice')) {
                $rowspan++;
                $sectionauto[] = array('position' => 60, 'level' => 1, 'module' => 'invoice', 'test' => $user->hasRight('facture', 'lire'), 'label' => $langs->trans("CustomersInvoices"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Invoices")));
            }
            if (isModEnabled('supplier_proposal')) {
                $langs->load("supplier_proposal");
                $rowspan++;
                $sectionauto[] = array('position' => 70, 'level' => 1, 'module' => 'supplier_proposal', 'test' => $user->hasRight('supplier_proposal', 'lire'), 'label' => $langs->trans("SupplierProposals"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("SupplierProposals")));
            }
            if (isModEnabled("supplier_order")) {
                $rowspan++;
                $sectionauto[] = array('position' => 80, 'level' => 1, 'module' => 'order_supplier', 'test' => $user->hasRight('fournisseur', 'commande', 'lire'), 'label' => $langs->trans("SuppliersOrders"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("SuppliersOrders")));
            }
            if (isModEnabled("supplier_invoice")) {
                $rowspan++;
                $sectionauto[] = array('position' => 90, 'level' => 1, 'module' => 'invoice_supplier', 'test' => $user->hasRight('fournisseur', 'facture', 'lire'), 'label' => $langs->trans("SuppliersInvoices"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("SupplierInvoices")));
            }
            if (isModEnabled('tax')) {
                $langs->load("compta");
                $rowspan++;
                $sectionauto[] = array('position' => 100, 'level' => 1, 'module' => 'tax', 'test' => $user->hasRight('tax', 'charges', 'lire'), 'label' => $langs->trans("SocialContributions"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("SocialContributions")));
                $rowspan++;
                $sectionauto[] = array('position' => 110, 'level' => 1, 'module' => 'tax-vat', 'test' => $user->hasRight('tax', 'charges', 'lire'), 'label' => $langs->trans("VAT"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("VAT")));
            }
            if (isModEnabled('salaries')) {
                $langs->load("compta");
                $rowspan++;
                $sectionauto[] = array('position' => 120, 'level' => 1, 'module' => 'salaries', 'test' => $user->hasRight('salaries', 'read'), 'label' => $langs->trans("Salaries"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Salaries")));
            }
            if (isModEnabled('project')) {
                $rowspan++;
                $sectionauto[] = array('position' => 130, 'level' => 1, 'module' => 'project', 'test' => 1, 'label' => $langs->trans("Projects"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Projects")));
                $rowspan++;
                $sectionauto[] = array('position' => 140, 'level' => 1, 'module' => 'project_task', 'test' => 1, 'label' => $langs->trans("Tasks"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Tasks")));
            }
            if (isModEnabled('intervention')) {
                $langs->load("interventions");
                $rowspan++;
                $sectionauto[] = array('position' => 150, 'level' => 1, 'module' => 'fichinter', 'test' => $user->hasRight('ficheinter', 'lire'), 'label' => $langs->trans("Interventions"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Interventions")));
            }
            if (isModEnabled('expensereport')) {
                $langs->load("trips");
                $rowspan++;
                $sectionauto[] = array('position' => 160, 'level' => 1, 'module' => 'expensereport', 'test' => $user->hasRight('expensereport', 'lire'), 'label' => $langs->trans("ExpenseReports"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("ExpenseReports")));
            }
            if (isModEnabled('holiday')) {
                $langs->load("holiday");
                $rowspan++;
                $sectionauto[] = array('position' => 170, 'level' => 1, 'module' => 'holiday', 'test' => $user->hasRight('holiday', 'read'), 'label' => $langs->trans("Holidays"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Holidays")));
            }
            if (isModEnabled("bank")) {
                $langs->load("banks");
                $rowspan++;
                $sectionauto[] = array('position' => 180, 'level' => 1, 'module' => 'banque', 'test' => $user->hasRight('banque', 'lire'), 'label' => $langs->trans("BankAccount"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("BankAccount")));
                $rowspan++;
                $sectionauto[] = array('position' => 190, 'level' => 1, 'module' => 'chequereceipt', 'test' => $user->hasRight('banque', 'lire'), 'label' => $langs->trans("CheckReceipt"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("CheckReceipt")));
            }
            if (isModEnabled('mrp')) {
                $langs->load("mrp");
                $rowspan++;
                $sectionauto[] = array('position' => 200, 'level' => 1, 'module' => 'mrp-mo', 'test' => $user->hasRight('mrp', 'read'), 'label' => $langs->trans("MOs"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("ManufacturingOrders")));
            }
            if (isModEnabled('recruitment')) {
                $langs->load("recruitment");
                $rowspan++;
                $sectionauto[] = array('position' => 210, 'level' => 1, 'module' => 'recruitment-recruitmentcandidature', 'test' => $user->hasRight('recruitment', 'read'), 'label' => $langs->trans("Candidatures"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("JobApplications")));
            }
            $rowspan++;
            $sectionauto[] = array('position' => 220, 'level' => 1, 'module' => 'user', 'test' => 1, 'label' => $langs->trans("Users"), 'desc' => $langs->trans("ECMDocsBy", $langs->transnoentitiesnoconv("Users")));
            
            $parameters = array();
            $reshook = $hookmanager->executeHooks('addSectionECMAuto', $parameters);
            if ($reshook > 0 && is_array($hookmanager->resArray) && count($hookmanager->resArray) > 0) {
                $res = $hookmanager->resArray[0];
                if (is_array($hookmanager->resArray[0])) {
                    $sectionauto = array_merge($sectionauto, $hookmanager->resArray);
                    $rowspan += count($hookmanager->resArray);
                } else {
                    $sectionauto[] = $hookmanager->resArray;
                    $rowspan++;
                }
            }
        }
        
        $head = ecm_prepare_dasboard_head();
        print dol_get_fiche_head($head, 'index_auto', '', -1, '');
        
        // Confirm remove file
        if ($action == 'deletefile' && empty($conf->use_javascript_ajax)) {
            print $form->formconfirm($_SERVER["PHP_SELF"].'?section='.$section.'&urlfile='.urlencode(GETPOST("urlfile")), $langs->trans('DeleteFile'), $langs->trans('ConfirmDeleteFile'), 'confirm_deletefile', '', '', 1);
        }
        
        // Start container
        ?>
        <div id="containerlayout">
        <div id="ecm-layout-north" class="toolbar largebutton">
        <?php
        
        print '<div class="inline-block toolbarbutton centpercent">';
        $url = ((!empty($conf->use_javascript_ajax) && !getDolGlobalString('MAIN_ECM_DISABLE_JS')) ? '#' : ($_SERVER["PHP_SELF"].'?action=refreshmanual'.($module ? '&amp;module='.$module : '').($section ? '&amp;section='.$section : '')));
        print '<a href="'.$url.'" class="inline-block valignmiddle toolbarbutton paddingtop" title="'.dol_escape_htmltag($langs->trans('Refresh')).'">';
        print img_picto('', 'refresh', 'id="refreshbutton"', 0, 0, 0, '', 'size15x marginrightonly');
        print '</a>';
        print '</div>';
        
        ?>
        </div>
        <div id="ecm-layout-west" class="inline-block">
        <?php
        
        // Generate form to confirm deletion
        if ($action == 'delete_section') {
            print $form->formconfirm($_SERVER["PHP_SELF"].'?section='.$section, $langs->trans('DeleteSection'), $langs->trans('ConfirmDeleteSection', $ecmdir->label), 'confirm_deletesection', '', '', 1);
        }
        
        if (empty($action) || $action == 'file_manager' || preg_match('/refresh/i', $action) || $action == 'deletefile') {
            print '<table class="liste centpercent noborder">'."\n";
            print '<tr class="liste_titre">'."\n";
            print '<th class="liste_titre" align="left" colspan="6">';
            print '&nbsp;'.$langs->trans("ECMSections");
            print '</th></tr>';
            
            if (count($sectionauto)) {
                $sectionauto = dol_sort_array($sectionauto, 'label', 'ASC', 1, 0);
                
                print '<tr>';
                print '<td colspan="6">';
                print '<div id="filetreeauto" class="ecmfiletree"><ul class="ecmjqft">';
                
                $arrayofmodulesforexternalusers = explode(',', getDolGlobalString('MAIN_MODULES_FOR_EXTERNAL'));
                
                foreach ($sectionauto as $key => $val) {
                    if (empty($val['test'])) {
                        continue;
                    }
                    
                    if ($user->socid > 0) {
                        if (!in_array($val['module'], $arrayofmodulesforexternalusers)) {
                            continue;
                        }
                    }
                    
                    print '<li class="directory collapsed">';
                    print '<a class="fmdirlia jqft ecmjqft" href="'.$_SERVER["PHP_SELF"].'?module='.urlencode($val['module']).'">';
                    print dolPrintLabel($val['label']);
                    print '</a>';
                    
                    print '<div class="ecmjqft">';
                    $htmltooltip = '<b>'.$langs->trans("ECMSection").'</b>: '.$val['label'].'<br>';
                    $htmltooltip .= '<b>'.$langs->trans("Type").'</b>: '.$langs->trans("ECMSectionAuto").'<br>';
                    $htmltooltip .= '<b>'.$langs->trans("ECMCreationUser").'</b>: '.$langs->trans("ECMTypeAuto").'<br>';
                    $htmltooltip .= '<b>'.$langs->trans("Description").'</b>: '.$val['desc'];
                    print $form->textwithpicto('', $htmltooltip, 1, 'info');
                    print '</div>';
                    
                    print '</li>';
                }
                
                print '</ul></div></td></tr>';
            }
            
            print "</table>";
        }
        
        ?>
        </div>
        <div id="ecm-layout-center" class="inline-block">
        <div class="pane-in ecm-in-layout-center">
        <div id="ecmfileview" class="ecmfileview">
        <?php
        
        $mode = 'noajax';
        $url = DOL_URL_ROOT.'/ecm/index_auto.php';
        include_once DOL_DOCUMENT_ROOT.'/core/ajax/ajaxdirpreview.php';
        
        ?>
        </div>
        </div>
        </div>
        </div>
        <?php
        
        if (!empty($conf->use_javascript_ajax) && !getDolGlobalString('MAIN_ECM_DISABLE_JS')) {
            include DOL_DOCUMENT_ROOT.'/ecm/tpl/enablefiletreeajax.tpl.php';
        }
        
        print dol_get_fiche_end();
        llxFooter();
        $db->close();
        
        return view('blank');
    }
    
    private function add(Request $request): RedirectResponse
    {
        global $db, $user, $langs;        if (!$user->hasRight('ecm', 'setup')) {
            accessforbidden();
        }
        
        $ecmdir = new EcmDirectory($db);
        $ecmdir->ref = 'NOTUSEDYET';
        $ecmdir->label = GETPOST("label");
        $ecmdir->description = GETPOST("desc");
        
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
        
        if (GETPOST('confirm') == 'yes') {
            $langs->load("other");
            $section = GETPOSTINT('section') ?: GETPOSTINT('section_id') ?: 0;
            
            $relativepath = '';
            if ($section) {
                $ecmdir = new EcmDirectory($db);
                $result = $ecmdir->fetch($section);
                if (!($result > 0)) {
                    dol_print_error($db, $ecmdir->error);
                    exit;
                }
                $relativepath = $ecmdir->getRelativePath();
            }
            
            $upload_dir = $conf->ecm->dir_output.($relativepath ? '/'.$relativepath : '');
            $file = $upload_dir."/".GETPOST('urlfile');
            
            $ret = dol_delete_file($file);
            if ($ret) {
                setEventMessages($langs->trans("FileWasRemoved", GETPOST('urlfile')), null, 'mesgs');
            } else {
                setEventMessages($langs->trans("ErrorFailToDeleteFile", GETPOST('urlfile')), null, 'errors');
            }
            
            if ($section) {
                $ecmdir->changeNbOfFiles('-');
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
        
        if (GETPOST('confirm') == 'yes') {
            $section = GETPOSTINT('section') ?: GETPOSTINT('section_id') ?: 0;
            
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
        
        // Compare both trees
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
        
        // Check if dirs in database exist on disk
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
