@php
/* Position Create View */
$title = $langs->trans("NewObject", $langs->transnoentitiesnoconv("Position"));
llxHeader('', $title, '');

print load_fiche_titre($langs->trans("NewObject", $langs->transnoentitiesnoconv("Position")), '', 'object_'.$object->picto);

print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="add">';
if ($backtopage) {
    print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
}
if ($backtopageforcancel) {
    print '<input type="hidden" name="backtopageforcancel" value="'.$backtopageforcancel.'">';
}
if ($fk_job > 0) {
    print '<input type="hidden" name="fk_job" value="'.$fk_job.'">';
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
