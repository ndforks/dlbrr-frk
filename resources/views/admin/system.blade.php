@php
$wikihelp = 'EN:System_information|FR:Information_système|ES:Información_del_sistema';
llxHeader('', $langs->trans("SystemInfo"), $wikihelp, '', 0, 0, '', '', '', 'mod-admin page-system');

print load_fiche_titre($langs->trans("SystemInfo"), '', 'title_setup');

print '<span class="opacitymedium">'.$langs->trans("SystemInfoDesc").'</span><br><br>';

print '<div class="div-table-responsive-no-min">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameter").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print '</tr>';

print '<tr class="oddeven">';
print '<td>'.$langs->trans("Version").'</td>';
print '<td>'.DOL_VERSION.'</td>';
print '</tr>';

print '</table>';
print '</div>';

llxFooter();
@endphp
