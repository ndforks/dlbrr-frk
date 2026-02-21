@php
$wikihelp = 'EN:First_setup|FR:Premiers_paramétrages|ES:Primeras_configuraciones';
llxHeader('', $langs->trans("Setup"), $wikihelp, '', 0, 0, '', '', '', 'mod-admin page-index');

print load_fiche_titre($langs->trans("SetupArea"), '', 'tools');

if (getDolGlobalString('MAIN_MOTD_SETUPPAGE')) {
    $conf->global->MAIN_MOTD_SETUPPAGE = preg_replace('/<br(\s[\sa-zA-Z_="]*)?\/?>/i', '<br>', getDolGlobalString('MAIN_MOTD_SETUPPAGE'));
    if (getDolGlobalString('MAIN_MOTD_SETUPPAGE')) {
        $i = 0;
        $reg = array();
        while (preg_match('/__\(([a-zA-Z|@]+)\)__/i', getDolGlobalString('MAIN_MOTD_SETUPPAGE'), $reg) && $i < 100) {
            $tmp = explode('|', $reg[1]);
            if (!empty($tmp[1])) {
                $langs->load($tmp[1]);
            }
            $conf->global->MAIN_MOTD_SETUPPAGE = preg_replace('/__\('.preg_quote($reg[1], '/').'\)__/i', $langs->trans($tmp[0]), getDolGlobalString('MAIN_MOTD_SETUPPAGE'));
            $i++;
        }
        print "\n<!-- Start of welcome text for setup page -->\n";
        print '<table class="centpercent notopnoleftnoright"><tr><td>';
        print dol_htmlentitiesbr(getDolGlobalString('MAIN_MOTD_SETUPPAGE'));
        print '</td></tr></table><br>';
        print "\n<!-- End of welcome text for setup page -->\n";
    }
}

print '<span class="opacitymedium hideonsmartphone">';
print $langs->trans("SetupDescription1").'<br>';

if ($setupcompanynotcomplete) {
    print $langs->trans("SetupDescription2", $langs->transnoentities("MenuCompanySetup"), $langs->transnoentities("Modules"));
}

print "<br><br>";
print '</span>';

$constkey = 'MAIN_INFO_SETUP_FOR_COUNTRY_'.$mysoc->country_code;
if (getDolGlobalString($constkey)) {
    $langs->load("errors");
    $warnpicto = img_warning('', 'style="padding-right: 6px;"');
    print '<div class="warning">'.$warnpicto.$langs->trans(getDolGlobalString($constkey)).'</div>';
}

print '<br>';

print '<section class="setupsection setupcompany cursorpointer">';
print img_picto('', 'company', 'class="paddingright valignmiddle double"');
print ' ';
print '<a class="nounderlineimp fontsize-1-1" href="'.DOL_URL_ROOT.'/admin/company.php?mainmenu=home'.(empty($setupcompanynotcomplete) ? '' : '&action=edit&token='.newToken()).'">'.$langs->transnoentities("Setup").' - '.$langs->transnoentities("MenuCompanySetup").'</a>';
print '<br><br>';
print $langs->trans("SetupDescription3b");
if (!empty($setupcompanynotcomplete)) {
    $langs->load("errors");
    $warnpicto = img_warning($langs->trans("WarningMandatorySetupNotComplete"), 'style="padding-right: 6px;"');
    print '<br><div class="warning"><a href="'.DOL_URL_ROOT.'/admin/company.php?mainmenu=home&action=edit&token='.newToken().'">'.$warnpicto.' '.$langs->trans("WarningMandatorySetupNotComplete").'</a></div>';
}
print '</a>';
print '</section>';

print '<br>';
print '<br>';

print '<section class="setupsection setupmodules cursorpointer">';
print img_picto('', 'cog', 'class="paddingright valignmiddle double"');
print ' ';
print '<a class="nounderlineimp fontsize-1-1" href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$langs->transnoentities("Setup").' - '.$langs->transnoentities("Modules").'</a>';
print '<br><br>'.$langs->trans("SetupDescription4b");
if ($nbmodulesnotautoenabled < getDolGlobalInt('MAIN_MIN_NB_ENABLED_MODULE_FOR_WARNING', 1)) {
    $langs->load("errors");
    $warnpicto = img_warning($langs->trans("WarningEnableYourModulesApplications"), 'style="padding-right: 6px;"');
    print '<br><div class="warning"><a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$warnpicto.$langs->trans("WarningEnableYourModulesApplications").'</a></div>';
}
print '</section>';

print '<br>';
print '<br>';
print '<br>';

print '<script>
    $(document).ready(function(){
        $(".setupcompany").click(function() {
            event.preventDefault();
            console.log("we click on setupcompany");
            window.location.href = "'.DOL_URL_ROOT.'/admin/company.php?mainmenu=home'.(empty($setupcompanynotcomplete) ? '' : '&action=edit').'";
        });
        $(".setupmodules").click(function() {
            event.preventDefault();
            console.log("we click on setupmodules");
            window.location.href = "'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home";
        });
    });
</script>';

$parameters = array();
$object = new stdClass();
$action = '';
$reshook = $hookmanager->executeHooks('addHomeSetup', $parameters, $object, $action);
print $hookmanager->resPrint;
if (empty($reshook)) {
    print '<br class="hideonsmartphone">';
    print '<div class="center"><div class="logo_setup"></div></div>';
}

llxFooter();
@endphp
