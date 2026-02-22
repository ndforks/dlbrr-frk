@extends('layouts.app')

@section('title', __('CronList'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('CronList') }}
                </h1>
                @if($permissiontoadd)
                    <a href="{{ route('cron.card', ['action' => 'create']) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                        {{ __('New') }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Search Filters --}}
        <form method="GET" action="{{ route('cron.list') }}" class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('CronLabel') }}
                    </label>
                    <input type="text" name="search_label" value="{{ $search_label }}" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Status') }}
                    </label>
                    <select name="search_status" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                        <option value="">{{ __('All') }}</option>
                        <option value="1" {{ $search_status == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ $search_status == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('CronModule') }}
                    </label>
                    <input type="text" name="search_module_name" value="{{ $search_module_name }}" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                </div>
            </div>

            <div class="mt-4 flex space-x-3">
                <button type="submit" name="button_search" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                    {{ __('Search') }}
                </button>
                <button type="submit" name="button_removefilter" 
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                    {{ __('RemoveFilter') }}
                </button>
            </div>
        </form>

        {{-- Cron Jobs Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('CronLabel') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('CronType') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('CronModule') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('CronDtNextLaunch') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        // Get cron jobs from database
                        $sql = "SELECT t.rowid, t.label, t.jobtype, t.module_name, t.datenextrun, t.status, t.processing 
                                FROM ".MAIN_DB_PREFIX."cronjob as t 
                                WHERE entity IN (0,".$conf->entity.")";
                        
                        if ($search_status !== '' && $search_status !== null) {
                            $sql .= " AND t.status = ".((int)$search_status);
                        }
                        if ($search_label) {
                            $sql .= " AND t.label LIKE '%".$db->escape($search_label)."%'";
                        }
                        if ($search_module_name) {
                            $sql .= " AND t.module_name LIKE '%".$db->escape($search_module_name)."%'";
                        }
                        
                        $sql .= " ORDER BY ".$sortfield." ".$sortorder;
                        $sql .= " LIMIT ".$limit." OFFSET ".$offset;
                        
                        $resql = $db->query($sql);
                        $num = $resql ? $db->num_rows($resql) : 0;
                    @endphp

                    @if($num > 0)
                        @for($i = 0; $i < $num; $i++)
                            @php
                                $obj = $db->fetch_object($resql);
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('cron.card', ['id' => $obj->rowid]) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                        {{ $obj->label }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $obj->jobtype }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $obj->module_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">
                                    @if($obj->datenextrun)
                                        {{ date('Y-m-d H:i', $db->jdate($obj->datenextrun)) }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($obj->status)
                                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 rounded">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded">
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if($permissiontoadd)
                                            <a href="{{ route('cron.card', ['id' => $obj->rowid, 'action' => 'edit']) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400" title="{{ __('Edit') }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if($permissiontoexecute && $obj->status)
                                            <a href="{{ route('cron.card', ['id' => $obj->rowid, 'action' => 'execute']) }}" 
                                               class="text-green-600 hover:text-green-900 dark:text-green-400" title="{{ __('CronExecute') }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if($permissiontodelete)
                                            <a href="{{ route('cron.card', ['id' => $obj->rowid, 'action' => 'delete']) }}" 
                                               class="text-red-600 hover:text-red-900 dark:text-red-400" title="{{ __('Delete') }}"
                                               onclick="return confirm('{{ __('CronConfirmDelete') }}')">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('CronNoJobs') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($num > 0)
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ __('Showing') }} {{ $offset + 1 }} {{ __('to') }} {{ min($offset + $limit, $offset + $num) }} {{ __('of') }} {{ $num }} {{ __('results') }}
                    </div>
                    <div class="flex space-x-2">
                        @if($page > 0)
                            <a href="{{ route('cron.list', array_merge(request()->query(), ['page' => $page - 1])) }}" 
                               class="px-3 py-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">
                                {{ __('Previous') }}
                            </a>
                        @endif
                        @if($num >= $limit)
                            <a href="{{ route('cron.list', array_merge(request()->query(), ['page' => $page + 1])) }}" 
                               class="px-3 py-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">
                                {{ __('Next') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
