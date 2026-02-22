@php
global $db, $langs, $user, $conf, $hookmanager;

llxHeader('', $langs->trans("Journals"), '', '', 0, 0, '', '', '', 'mod-accountancy page-journal-index');

print load_fiche_titre($langs->trans("Journals"), '', 'accountancy');

print '<div class="div-table-responsive">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<th>'.$langs->trans("JournalType").'</th>';
print '</tr>';

foreach ($journals as $journal) {
    print '<tr class="oddeven">';
    print '<td><a href="'.$journal['url'].'">'.$langs->trans($journal['name']).'</a></td>';
    print '</tr>';
}

print '</table>';
print '</div>';

llxFooter();
$db->close();
@endphp
