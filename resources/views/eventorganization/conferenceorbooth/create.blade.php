@php
global $db, $conf, $langs, $user;

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';

$form = new Form($db);
$formfile = new FormFile($db);
$formproject = new FormProjets($db);

$title = $langs->trans("NewObject", $langs->transnoentitiesnoconv("ConferenceOrBooth"));
$help_url = 'EN:Module_Event_Organization';

llxHeader('', $title, $help_url, '', 0, 0, '', '', '', 'mod-eventorganization page-card');

print load_fiche_titre($title, '', 'object_'.$object->picto);

$backtopage = GETPOST('backtopage', 'alpha');
$backtopageforcancel = GETPOST('backtopageforcancel', 'alpha');

print '<form method="POST" action="'.dolBuildUrl($_SERVER["PHP_SELF"]).'">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="add">';
if ($withproject) {
    print '<input type="hidden" name="withproject" value="'.$withproject.'">';
    print '<input type="hidden" name="fk_project" value="'.GETPOSTINT('fk_project').'">';
}
if ($backtopage) {
    print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
}
if ($backtopageforcancel) {
    print '<input type="hidden" name="backtopageforcancel" value="'.$backtopageforcancel.'">';
}

print dol_get_fiche_head(array(), '');

print '<table class="border centpercent tableforfieldcreate">'."\n";

include DOL_DOCUMENT_ROOT.'/core/tpl/commonfields_add.tpl.php';
include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_add.tpl.php';

print '</table>'."\n";

print dol_get_fiche_end();

print $form->buttonsSaveCancel("Create");

print '</form>';

llxFooter();
$db->close();
@endphp
