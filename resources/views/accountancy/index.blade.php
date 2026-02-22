@php
global $db, $langs, $user, $conf, $hookmanager, $mysoc;

$help_url = 'EN:Module_Double_Entry_Accounting#Setup|FR:Module_Comptabilit&eacute;_en_Partie_Double#Configuration';
llxHeader('', $langs->trans("AccountancyArea"), $help_url, '', 0, 0, '', '', '', 'mod-accountancy page-index');

$showtutorial = '';
if (!$helpisexpanded) {
    $showtutorial  = '<div class="right"><a href="#" id="show_hide">';
    $showtutorial .= img_picto('', 'chevron-down', 'class="show_hide_picto pictofixedwidth"');
    $showtutorial .= $langs->trans("ShowTutorial");
    $showtutorial .= '</a></div>';
    
    $showtutorial .= '<script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#show_hide").click(function () {
                console.log("We click on show-hide");
                if ($(".idfaq2").is(":hidden")) {
                    jQuery( ".idfaq2" ).show();
                    jQuery( ".show_hide_picto" ).removeClass("fa-chevron-up").addClass("fa-chevron-down");
                } else {
                    jQuery( ".idfaq2" ).hide();
                    jQuery( ".show_hide_picto" ).removeClass("fa-chevron-down").addClass("fa-chevron-up");
                }
                jQuery( ".idfaq" ).toggle({
                    duration: 400,
                });
            });
        });
        </script>';
}

if (isModEnabled('accounting')) {
    print load_fiche_titre($langs->trans("AccountancyArea"), empty($resultboxes['selectboxlist']) ? '' : $resultboxes['selectboxlist'], 'accountancy', 0, '', '', $showtutorial);
    
    if (!$helpisexpanded && empty($resultboxes['boxlista']) && empty($resultboxes['boxlistb'])) {
        print '<div class="opacitymedium idfaq2"><br>'.$langs->trans("ClickOnUseTutorialForHelp", $langs->transnoentities("ShowTutorial"))."</div>\n";
    }
    
    print '<div class="'.($helpisexpanded ? '' : 'hideobject').' idfaq">';
    print "<br>\n";
    print '<span class="opacitymedium">'.$langs->trans("AccountancyAreaDescIntro")."</span><br>\n";
    
    if ($user->hasRight('accounting', 'chartofaccount')) {
        print '<br>';
        print load_fiche_titre('<span class="fa fa-calendar"></span> '.$langs->trans("AccountancyAreaDescActionOnce"), '', '', 0, '', 'nomarginbottom')."\n";
        print '<hr>';
        print "<br>\n";
        
        $step = 0;
        $step++;
        $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescJournalSetup", $step, '{s}');
        $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/admin/journals_list.php?id=35&&leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("AccountingJournals").'</strong></a>', $s);
        print $s;
        print "<br>\n";
        
        $step++;
        $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescChartModel", $step, '{s}');
        $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/admin/accountmodel.php?search_country_id='.$mysoc->country_id.'&leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("Pcg_version").'</strong></a>', $s);
        print $s;
        print "<br>\n";
        
        $step++;
        $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescChart", $step, '{s}');
        $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/admin/account.php?leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("Chartofaccounts").'</strong></a>', $s);
        print $s;
        if ($pcgver > 0) {
            print ' <span class="opacitymedium">('.$langs->trans("CurrentChartOfAccount").': '.$pcgversion.')</span>';
        }
        print "<br>\n";
        
        if (getDolGlobalString('ACCOUNTANCY_FISCAL_PERIOD_MODE') != 'blockedonclosed') {
            $step++;
            $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescFiscalPeriod", $step, '{s}');
            $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/admin/fiscalyear.php?leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("FiscalPeriod").'</strong></a>', $s);
            print $s;
            print "<br>\n";
        }
        
        print "<br>\n";
        print $langs->trans("AccountancyAreaDescActionOnceBis");
        print "<br>\n";
        print "<br>\n";
        
        $step++;
        $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescDefault", $step, '{s}');
        $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/admin/defaultaccounts.php?leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("MenuDefaultAccounts").'</strong></a>', $s);
        print $s;
        print "<br>\n";
        
        $step++;
        $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescBank", $step, '{s}')."\n";
        $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/compta/bank/list.php?leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("MenuBankAccounts").'</strong></a>', $s);
        print $s;
        print "<br>\n";
        
        $step++;
        $textlink = '<a href="'.DOL_URL_ROOT.'/admin/dict.php?id=10&from=accountancy&leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("MenuVatAccounts").'</strong></a>';
        $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescVat", $step, '{s}');
        $s = str_replace('{s}', $textlink, $s);
        print $s;
        print "<br>\n";
        
        if (isModEnabled('tax')) {
            $textlink = '<a href="'.DOL_URL_ROOT.'/admin/dict.php?id=7&from=accountancy&leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("MenuTaxAccounts").'</strong></a>';
            $step++;
            $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescContrib", $step, '{s}');
            $s = str_replace('{s}', $textlink, $s);
            print $s;
            print "<br>\n";
        }
        
        if (isModEnabled('expensereport')) {
            $step++;
            $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescExpenseReport", $step, '{s}');
            $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/admin/dict.php?id=17&from=accountancy&leftmenu=accountancy_admin"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("MenuExpenseReportAccounts").'</strong></a>', $s);
            print $s;
            print "<br>\n";
        }
        
        $step++;
        $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescProd", $step, '{s}');
        $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/admin/productaccount.php?leftmenu=accountancy_admin" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("Setup").' - '.$langs->transnoentitiesnoconv("ProductsBinding").'</strong></a>', $s);
        print $s;
        print "<br>\n";
        
        print '<br>';
    }
    
    print "<br>\n";
    print load_fiche_titre('<span class="fa fa-calendar"></span> '.$langs->trans("AccountancyAreaDescActionFreq"), '', '', 0, '', 'nomarginbottom')."\n";
    print '<hr>';
    print "<br>\n";
    $step = 0;
    
    $langs->loadLangs(array('bills', 'trips'));
    
    $step++;
    $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescBind", chr(64 + $step), $langs->transnoentitiesnoconv("BillsCustomers"), '{s}')."\n";
    $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/customer/index.php" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("TransferInAccounting").' - '.$langs->transnoentitiesnoconv("CustomersVentilation").'</strong></a>', $s);
    print $s;
    print "<br>\n";
    
    $step++;
    $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescBind", chr(64 + $step), $langs->transnoentitiesnoconv("BillsSuppliers"), '{s}')."\n";
    $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/supplier/index.php" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("TransferInAccounting").' - '.$langs->transnoentitiesnoconv("SuppliersVentilation").'</strong></a>', $s);
    print $s;
    print "<br>\n";
    
    if (isModEnabled('expensereport') || isModEnabled('deplacement')) {
        $step++;
        $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescBind", chr(64 + $step), $langs->transnoentitiesnoconv("ExpenseReports"), '{s}')."\n";
        $s = str_replace('{s}', '<a href="'.DOL_URL_ROOT.'/accountancy/expensereport/index.php" target="setupaccountancy"><strong>'.$langs->transnoentitiesnoconv("TransferInAccounting").' - '.$langs->transnoentitiesnoconv("ExpenseReportsVentilation").'</strong></a>', $s);
        print $s;
        print "<br>\n";
    }
    
    $step++;
    $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescWriteRecords", chr(64 + $step), $langs->transnoentitiesnoconv("TransferInAccounting").' - '.$langs->transnoentitiesnoconv("RegistrationInAccounting"), $langs->transnoentitiesnoconv("WriteBookKeeping"))."\n";
    print $s;
    print "<br>\n";
    
    $step++;
    $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescAnalyze", chr(64 + $step))."<br>\n";
    print $s;
    
    $step++;
    $s = img_picto('', 'puce').' '.$langs->trans("AccountancyAreaDescClosePeriod", chr(64 + $step))."<br>\n";
    print $s;
    
    if (!empty($resultboxes['boxlista']) || !empty($resultboxes['boxlistb'])) {
        print "<br>\n";
        print '<br>';
    }
    
    print '</div>';
    
    print '<div class="clearboth"></div>';
} elseif (isModEnabled('comptabilite')) {
    print load_fiche_titre($langs->trans("AccountancyArea"), '', 'accountancy');
    
    print '<span class="opacitymedium">'.$langs->trans("Module10Desc")."</span>\n";
    print "<br>";
} else {
    print load_fiche_titre($langs->trans("AccountancyArea"), '', 'accountancy');
}

print $boxlist;

llxFooter();
$db->close();
@endphp
