@extends('layouts.app')

@section('title', 'Position Card')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Position Details
        </h1>

        @php
            global $db, $conf, $langs, $user, $hookmanager, $fk_job, $objectposition, $job;
            global $permissiontodelete, $permissiontoadd, $sortfield, $sortorder, $page, $limit, $offset;
            
            if (defined('DOL_DOCUMENT_ROOT')) {
                require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
                require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
                require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
            }
            
            // Legacy position card with complex list rendering
            // This displays position information and associated job positions list
        @endphp
    </div>
@endsection
