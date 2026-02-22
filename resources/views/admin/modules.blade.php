@extends('layouts.app')

@section('title', 'Modules Setup')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            Modules Setup
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Enable or disable Dolibarr modules and manage their configuration.
        </p>

        <x-card title="Available Modules">
            @php
                global $db, $langs, $conf, $user;
                
                if (!defined('CSRFCHECK_WITH_TOKEN') && (empty($_GET['action']) || $_GET['action'] != 'reset')) {
                    define('CSRFCHECK_WITH_TOKEN', '1');
                }
                
                if (defined('DOL_DOCUMENT_ROOT')) {
                    require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/lib/geturl.lib.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/class/events.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';
                    require_once DOL_DOCUMENT_ROOT.'/admin/remotestore/class/externalModules.class.php';
                }
                
                $page = GETPOSTINT('page');
                $optioncss = GETPOST('optioncss', 'aZ09');
                $sortfield = GETPOST('sortfield', 'aZ09');
                $sortorder = GETPOST('sortorder', 'aZ09');
                $mode = GETPOST('mode', 'alpha');
                $value = GETPOST('value', 'alpha');
            @endphp

            <form action="{{ $_SERVER['PHP_SELF'] }}" method="POST" name="formulaire" class="space-y-4">
                @csrf
                <input type="hidden" name="action" value="set">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-blue-600 dark:bg-blue-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">
                                    Modules
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">
                                    Version
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase">
                                    Info
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            {{-- Module list will be rendered by legacy code --}}
                        </tbody>
                    </table>
                </div>
            </form>

            @php
                if (isset($db)) {
                    $db->close();
                }
            @endphp
        </x-card>
    </div>
@endsection
