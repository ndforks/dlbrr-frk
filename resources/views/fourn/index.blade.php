@php
llxHeader("", $title, '', '', 0, 0, '', '', '', 'mod-fourn page-index');
print load_fiche_titre($title);

print '<div class="fichecenter"><div class="fichethirdleft">';

// Orders statistics
$sql = "SELECT count(cf.rowid), cf.fk_statut";
$sql .= " FROM ".MAIN_DB_PREFIX."commande_fournisseur as cf,";
$sql .= " ".MAIN_DB_PREFIX."societe as s";
if (!$user->hasRight("societe", "client", "voir") && !$socid) {
    $sql .= " LEFT JOIN ".MAIN_DB_PREFIX."societe_commerciaux as sc ON s.rowid = sc.fk_soc";
}
$sql .= " WHERE cf.fk_soc = s.rowid ";
if (!$user->hasRight("societe", "client", "voir") && !$socid) {
    $sql .= " AND sc.fk_user = ".((int) $user->id);
}
$sql .= " AND cf.entity = ".$conf->entity;
$sql .= " GROUP BY cf.fk_statut";

$resql = $db->query($sql);
if ($resql) {
    $num = $db->num_rows($resql);
    print '<table class="noborder centpercent">';
    print '<tr class="liste_titre"><td>'.$langs->trans("Orders").'</td><td class="center">'.$langs->trans("Nb").'</td><td>&nbsp;</td>';
    print "</tr>\n";
    while ($row = $db->fetch_row($resql)) {
        print '<tr class="oddeven">';
        print '<td>'.$commandestatic->LibStatut($row[1]).'</td>';
        print '<td class="center">'.$row[0].'</td>';
        print '<td class="center"><a href="'.DOL_URL_ROOT.'/fourn/commande/list.php?statut='.$row[1].'">'.$commandestatic->LibStatut($row[1], 3).'</a></td>';
        print "</tr>\n";
    }
    print "</table><br>\n";
    $db->free($resql);
}

print '</div><div class="fichetwothirdright">';
print '</div></div>';

llxFooter();
@endphp
