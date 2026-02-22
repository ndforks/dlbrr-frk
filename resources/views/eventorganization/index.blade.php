@php
global $db, $conf, $langs, $user;

$form = new Form($db);
$formfile = new FormFile($db);

llxHeader('', $title, $helpUrl, '', 0, 0, '', '', '', 'mod-eventorganization page-index');

print load_fiche_titre($langs->trans("EventOrganizationArea"), '', 'eventorganization.png@eventorganization');

print '<div class="fichecenter"><div class="fichethirdleft">';
print '</div><div class="fichetwothirdright">';
print '</div></div>';

llxFooter();
$db->close();
@endphp
