@extends('layouts.app')

@section('title', 'System Information')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            System Information
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            View system configuration and version details
        </p>

        <x-card title="System Details">
            <x-table :columns="['Parameter', 'Value']">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">Version</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ defined('DOL_VERSION') ? DOL_VERSION : 'N/A' }}</td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">PHP Version</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ phpversion() }}</td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">Laravel Version</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ app()->version() }}</td>
                </tr>
            </x-table>
        </x-card>
    </div>
@endsection
