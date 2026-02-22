@extends('layouts.app')

@section('title', 'Conference or Booth Details')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Conference or Booth Details
        </h1>

        <x-card title="Event Information">
            @php
                global $db, $conf, $langs, $user, $hookmanager, $dolibarr_main_url_root;

                if (defined('DOL_DOCUMENT_ROOT')) {
                    require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/lib/project.lib.php';
                    require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/eventorganization/lib/eventorganization_conferenceorbooth.lib.php';
                }
                
                // Legacy detail rendering code
            @endphp
        </x-card>
    </div>
@endsection
