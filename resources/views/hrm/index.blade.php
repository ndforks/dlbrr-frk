@extends('layouts.app')

@section('title', 'HRM Area')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Human Resources Management
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @php
                global $db, $langs, $conf, $user, $setupcompanynotcomplete, $childids, $max, $hookmanager;
                
                if (defined('DOL_DOCUMENT_ROOT')) {
                    require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/lib/usergroups.lib.php';
                    require_once DOL_DOCUMENT_ROOT.'/user/class/user.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/user/class/usergroup.class.php';
                }
                
                if (isModEnabled('deplacement')) {
                    require_once DOL_DOCUMENT_ROOT.'/compta/deplacement/class/deplacement.class.php';
                }
                if (isModEnabled('expensereport')) {
                    require_once DOL_DOCUMENT_ROOT.'/expensereport/class/expensereport.class.php';
                }
                if (isModEnabled('recruitment')) {
                    require_once DOL_DOCUMENT_ROOT.'/recruitment/class/recruitmentcandidature.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/recruitment/class/recruitmentjobposition.class.php';
                }
                if (isModEnabled('holiday')) {
                    require_once DOL_DOCUMENT_ROOT.'/holiday/class/holiday.class.php';
                }
                
                // Legacy HRM dashboard rendering
                // This would render widgets, statistics, and recent activity
            @endphp
        </div>
    </div>
@endsection
