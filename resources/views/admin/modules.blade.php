@php
global $db;

if (!defined('CSRFCHECK_WITH_TOKEN') && (empty($_GET['action']) || $_GET['action'] != 'reset')) {
    define('CSRFCHECK_WITH_TOKEN', '1');
}

require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/geturl.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/events.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';
require_once DOL_DOCUMENT_ROOT.'/admin/remotestore/class/externalModules.class.php';

$page = GETPOSTINT('page');
$optioncss = GETPOST('optioncss', 'aZ09');
$sortfield = GETPOST('sortfield', 'aZ09');
$sortorder = GETPOST('sortorder', 'aZ09');
$mode = GETPOST('mode', 'alpha');
$value = GETPOST('value', 'alpha');

$wikihelp = 'EN:First_setup|FR:Premiers_paramétrages|ES:Primeras_configuraciones';
llxHeader('', $langs->trans("Setup"), $wikihelp, '', 0, 0, '', '', '', 'mod-admin page-modules');

print load_fiche_titre($langs->trans("ModulesSetup"), '', 'title_setup');

print '<span class="opacitymedium">'.$langs->trans("ModulesDesc").'</span><br><br>';

print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST" name="formulaire">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="set">';

print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Modules").'</td>';
print '<td class="center">'.$langs->trans("Version").'</td>';
print '<td class="center">'.$langs->trans("Status").'</td>';
print '<td class="center">'.$langs->trans("ShortInfo").'</td>';
print '</tr>';

print '</table>';

print '</form>';

llxFooter();
$db->close();
@endphp
