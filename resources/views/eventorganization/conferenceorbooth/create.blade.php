@extends('layouts.app')

@section('title', 'Create Conference or Booth')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Create Conference or Booth
        </h1>

        <x-card title="Event Information">
            @php
                global $db, $conf, $langs, $user;

                if (defined('DOL_DOCUMENT_ROOT')) {
                    require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
                }

                $form = new Form($db);
                $formfile = new FormFile($db);
                $formproject = new FormProjets($db);
                
                // Legacy form rendering code
                // The actual form fields would be rendered here
            @endphp
        </x-card>
    </div>
@endsection
