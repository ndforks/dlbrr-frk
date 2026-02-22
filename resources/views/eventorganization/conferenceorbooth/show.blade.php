@php
global $db, $conf, $langs, $user, $hookmanager, $dolibarr_main_url_root;

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/project.lib.php';
require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';
require_once DOL_DOCUMENT_ROOT.'/eventorganization/lib/eventorganization_conferenceorbooth.lib.php';

$form = new Form($db);
$formfile = new FormFile($db);
$formproject = new FormProjets($db);

$title = $langs->trans("ConferenceOrBooth");
$help_url = 'EN:Module_Event_Organization';

llxHeader('', $title, $help_url, '', 0, 0, '', '', '', 'mod-eventorganization page-card');

$withProjectUrl = $withproject ? '&withproject=1' : '';

if ($withproject) {
    $head = project_prepare_head($project);
    print dol_get_fiche_head($head, 'eventorganisation', $langs->trans("Project"), -1, ($project->public ? 'projectpub' : 'project'), 0, '', '');
    
    $linkback = '<a href="'.DOL_URL_ROOT.'/projet/list.php?restore_lastsearch_values=1">'.$langs->trans("BackToList").'</a>';
    
    $morehtmlref = '<div class="refidno">';
    $morehtmlref .= $project->title;
    if (isset($project->thirdparty->id) && $project->thirdparty->id > 0) {
        $morehtmlref .= '<br>'.$project->thirdparty->getNomUrl(1, 'project');
    }
    $morehtmlref .= '</div>';
    
    dol_banner_tab($project, 'project_ref', $linkback, 1, 'ref', 'ref', $morehtmlref);
    
    print '<div class="fichecenter">';
    print '<div class="fichehalfleft">';
    print '<div class="underbanner clearboth"></div>';
    print '<table class="border tableforfield centpercent">';
    
    // Display project info
    if (getDolGlobalString('PROJECT_USE_OPPORTUNITIES') || !getDolGlobalString('PROJECT_HIDE_TASKS') || isModEnabled('eventorganization')) {
        print '<tr><td class="tdtop">'.$langs->trans("Usage").'</td><td>';
        if (getDolGlobalString('PROJECT_USE_OPPORTUNITIES')) {
            print '<input type="checkbox" disabled name="usage_opportunity"'.($project->usage_opportunity ? ' checked="checked"' : '').'> ';
            print $form->textwithpicto($langs->trans("ProjectFollowOpportunity"), $langs->trans("ProjectFollowOpportunity"));
            print '<br>';
        }
        if (!getDolGlobalString('PROJECT_HIDE_TASKS')) {
            print '<input type="checkbox" disabled name="usage_task"'.($project->usage_task ? ' checked="checked"' : '').'> ';
            print $form->textwithpicto($langs->trans("ProjectFollowTasks"), $langs->trans("ProjectFollowTasks"));
            print '<br>';
        }
        if (isModEnabled('eventorganization')) {
            print '<input type="checkbox" disabled name="usage_organize_event"'.($project->usage_organize_event ? ' checked="checked"' : '').'"> ';
            print $form->textwithpicto($langs->trans("ManageOrganizeEvent"), $langs->trans("EventOrganizationDescriptionLong"));
        }
        print '</td></tr>';
    }
    
    print '<tr><td>'.$langs->trans("Budget").'</td><td>';
    if (strcmp($project->budget_amount, '')) {
        print '<span class="amount">'.price($project->budget_amount, 0, $langs, 1, 0, 0, $conf->currency).'</span>';
    }
    print '</td></tr>';
    
    print '<tr><td>'.$langs->trans("Dates").' ('.$langs->trans("Project").')</td><td>';
    $start = dol_print_date($project->date_start, 'day');
    print($start ? $start : '?');
    $end = dol_print_date($project->date_end, 'day');
    print ' - ';
    print($end ? $end : '?');
    if ($project->hasDelay()) {
        print img_warning("Late");
    }
    print '</td></tr>';
    
    print '</table>';
    print '</div>';
    
    print '<div class="fichehalfright">';
    print '<div class="underbanner clearboth"></div>';
    print '<table class="border tableforfield centpercent">';
    
    if (isModEnabled('category')) {
        print '<tr><td class="titlefield valignmiddle">'.$langs->trans("Categories").'</td><td class="valuefield">';
        print $form->showCategories($project->id, Categorie::TYPE_PROJECT, 1);
        print "</td></tr>";
    }
    
    print '<tr><td class="titlefield tdtop">'.$langs->trans("Description").'</td><td class="valuefield">';
    print dol_htmlentitiesbr($project->description);
    print '</td></tr>';
    
    print '</table>';
    print '</div>';
    print '</div>';
    
    print '<div class="clearboth"></div>';
    print dol_get_fiche_end();
    print '<br>';
}

if ($object->id > 0) {
    $head = conferenceorboothPrepareHead($object, $withproject);
    print dol_get_fiche_head($head, 'card', $langs->trans("ConferenceOrBooth"), -1, $object->picto);
    
    $linkback = '<a href="'.dol_buildpath('/eventorganization/conferenceorbooth_list.php', 1).'?projectid='.$object->fk_project.$withProjectUrl.'">'.$langs->trans("BackToList").'</a>';
    
    $morehtmlref = '<div class="refidno"></div>';
    dol_banner_tab($object, 'ref', $linkback, 1, 'ref', 'ref', $morehtmlref);
    
    print '<div class="fichecenter">';
    print '<div class="fichehalfleft">';
    print '<div class="underbanner clearboth"></div>';
    print '<table class="border centpercent tableforfield">'."\n";
    
    $keyforbreak = 'num_vote';
    include DOL_DOCUMENT_ROOT.'/core/tpl/commonfields_view.tpl.php';
    include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_view.tpl.php';
    
    print '</table>';
    print '</div>';
    print '</div>';
    
    print '<div class="clearboth"></div>';
    print dol_get_fiche_end();
    
    $permissiontoadd = $user->hasRight('project', 'write');
    $permissiontodelete = $user->hasRight('project', 'delete') || ($permissiontoadd && isset($object->status) && $object->status == $object::STATUS_DRAFT);
    
    print '<div class="tabsAction">'."\n";
    
    if (empty($user->socid)) {
        print dolGetButtonAction('', $langs->trans('SendMail'), 'email', $_SERVER["PHP_SELF"].'?id='.$object->id.$withProjectUrl.'&action=presend&token='.newToken().'&mode=init#formmailbeforetitle');
    }
    
    print dolGetButtonAction('', $langs->trans('Modify'), 'default', $_SERVER["PHP_SELF"].'?id='.$object->id.$withProjectUrl.'&action=edit&token='.newToken(), '', $permissiontoadd);
    
    print dolGetButtonAction('', $langs->trans('ToClone'), 'default', $_SERVER['PHP_SELF'].'?id='.$object->id.$withProjectUrl.'&socid='.$object->socid.'&action=clone&token='.newToken(), '', $permissiontoadd);
    
    print dolGetButtonAction($langs->trans('Delete'), '', 'delete', $_SERVER['PHP_SELF'].'?id='.$object->id.$withProjectUrl.'&action=delete&token='.newToken(), '', $permissiontodelete);
    
    print '</div>'."\n";
    
    print '<div class="fichecenter"><div class="fichehalfleft">';
    print '<a name="builddoc"></a>';
    
    $objref = dol_sanitizeFileName($object->ref);
    $relativepath = $objref.'/'.$objref.'.pdf';
    $filedir = $conf->eventorganization->dir_output.'/'.$object->element.'/'.$objref;
    $urlsource = $_SERVER["PHP_SELF"]."?id=".$object->id;
    $genallowed = $user->hasRight('project', 'read');
    $delallowed = $user->hasRight('project', 'write');
    print $formfile->showdocuments('eventorganization:ConferenceOrBooth', $object->element.'/'.$objref, $filedir, $urlsource, 0, $delallowed, $object->model_pdf, 0, 0, 0, 28, 0, '', '', '', $langs->defaultlang);
    
    print '</div><div class="fichehalfright">';
    print '</div></div>';
}

llxFooter();
$db->close();
@endphp
