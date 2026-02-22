@extends('layouts.app')

@section('title', 'Setup Area')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @php
            global $db, $langs, $conf, $user, $mysoc, $setupcompanynotcomplete, $nbmodulesnotautoenabled, $hookmanager;
            
            // Message of the day for setup
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
                    print '<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">';
                    print dol_htmlentitiesbr(getDolGlobalString('MAIN_MOTD_SETUPPAGE'));
                    print '</div>';
                }
            }
        @endphp

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
            Setup Area
        </h1>

        <div class="text-gray-600 dark:text-gray-400 mb-6">
            @php
                print '<p class="mb-2">'.$langs->trans("SetupDescription1").'</p>';
                if ($setupcompanynotcomplete) {
                    print '<p class="mb-2">'.$langs->trans("SetupDescription2", $langs->transnoentities("MenuCompanySetup"), $langs->transnoentities("Modules")).'</p>';
                }
                
                $constkey = 'MAIN_INFO_SETUP_FOR_COUNTRY_'.$mysoc->country_code;
                if (getDolGlobalString($constkey)) {
                    $langs->load("errors");
                    print '<div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mt-4">';
                    print '<p class="text-yellow-800 dark:text-yellow-200">'.$langs->trans(getDolGlobalString($constkey)).'</p>';
                    print '</div>';
                }
            @endphp
        </div>

        @php
            // Setup cards
            print '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
            
            // Company setup card
            print '<a href="'.DOL_URL_ROOT.'/admin/company.php?mainmenu=home'.(empty($setupcompanynotcomplete) ? '' : '&action=edit&token='.newToken()).'" class="setupcompany">';
            print '<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer">';
            print '<h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">'.$langs->transnoentities("Setup").' - '.$langs->transnoentities("MenuCompanySetup").'</h3>';
            print '<p class="text-gray-600 dark:text-gray-400">'.$langs->trans("SetupDescription3b").'</p>';
            if (!empty($setupcompanynotcomplete)) {
                $langs->load("errors");
                print '<div class="mt-2 text-yellow-600 dark:text-yellow-400">'.$langs->trans("WarningMandatorySetupNotComplete").'</div>';
            }
            print '</div>';
            print '</a>';
            
            // Modules setup card
            print '<a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home" class="setupmodules">';
            print '<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer">';
            print '<h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">'.$langs->transnoentities("Setup").' - '.$langs->transnoentities("Modules").'</h3>';
            print '<p class="text-gray-600 dark:text-gray-400">'.$langs->trans("SetupDescription4b").'</p>';
            if ($nbmodulesnotautoenabled < getDolGlobalInt('MAIN_MIN_NB_ENABLED_MODULE_FOR_WARNING', 1)) {
                $langs->load("errors");
                print '<div class="mt-2 text-yellow-600 dark:text-yellow-400">'.$langs->trans("WarningEnableYourModulesApplications").'</div>';
            }
            print '</div>';
            print '</a>';
            
            print '</div>';
            
            // Hooks
            $parameters = array();
            $object = new stdClass();
            $action = '';
            $reshook = $hookmanager->executeHooks('addHomeSetup', $parameters, $object, $action);
            print $hookmanager->resPrint;
            
            if (isset($db)) {
                $db->close();
            }
        @endphp
    </div>

    @push('scripts')
    <script>
        $(document).ready(function(){
            $(".setupcompany").click(function(event) {
                event.preventDefault();
                window.location.href = "{{ url('/admin/company.php?mainmenu=home') }}";
            });
            $(".setupmodules").click(function(event) {
                event.preventDefault();
                window.location.href = "{{ url('/admin/modules.php?mainmenu=home') }}";
            });
        });
    </script>
    @endpush
@endsection
