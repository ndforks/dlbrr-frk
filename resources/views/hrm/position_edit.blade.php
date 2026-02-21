@php
/* Position Edit View */
$title = $langs->trans("Position");
llxHeader('', $title, '');

print load_fiche_titre($langs->trans("Position"), '', 'object_'.$object->picto);

print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="update">';
print '<input type="hidden" name="id" value="'.$object->id.'">';
if ($backtopage) {
    print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
}
if ($backtopageforcancel) {
    print '<input type="hidden" name="backtopageforcancel" value="'.$backtopageforcancel.'">';
}

print dol_get_fiche_head(array(), '');

print '<table class="border centpercent tableforfieldcreate">'."\n";

include DOL_DOCUMENT_ROOT.'/core/tpl/commonfields_edit.tpl.php';
include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_edit.tpl.php';

print '</table>'."\n";

print dol_get_fiche_end();

print $form->buttonsSaveCancel();

print '</form>';

llxFooter();
$db->close();
@endphp
