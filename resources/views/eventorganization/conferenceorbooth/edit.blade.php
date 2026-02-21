@php
global $db, $conf, $langs, $user;

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';

$form = new Form($db);
$formfile = new FormFile($db);
$formproject = new FormProjets($db);

$title = $langs->trans("ConferenceOrBooth");
$help_url = 'EN:Module_Event_Organization';

llxHeader('', $title, $help_url, '', 0, 0, '', '', '', 'mod-eventorganization page-card');

print load_fiche_titre($langs->trans("ConferenceOrBooth"), '', 'object_'.$object->picto);

$withProjectUrl = $withproject ? '&withproject=1' : '';
$backtopage = GETPOST('backtopage', 'alpha');
$backtopageforcancel = GETPOST('backtopageforcancel', 'alpha');

print '<form method="POST" action="'.dolBuildUrl($_SERVER["PHP_SELF"]).'">';
print '<input type="hidden" name="token" value="'.newToken().'">';
if (!empty($withProjectUrl)) {
    print '<input type="hidden" name="withproject" value="1">';
}
print '<input type="hidden" name="action" value="update">';
print '<input type="hidden" name="id" value="'.$object->id.'">';
if ($backtopage) {
    print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
}
if ($backtopageforcancel) {
    print '<input type="hidden" name="backtopageforcancel" value="'.$backtopageforcancel.'">';
}

print dol_get_fiche_head();

print '<table class="border centpercent tableforfieldedit">'."\n";

include DOL_DOCUMENT_ROOT.'/core/tpl/commonfields_edit.tpl.php';
include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_edit.tpl.php';

print '</table>';

print dol_get_fiche_end();

print $form->buttonsSaveCancel();

print '</form>';

llxFooter();
$db->close();
@endphp
