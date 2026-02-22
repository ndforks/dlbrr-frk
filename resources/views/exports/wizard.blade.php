@extends('layouts.app')

@section('title', __('Export'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-6">{{ __('ExportDataset') }}</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="mb-4">
            <p class="text-gray-700 dark:text-gray-300">
                {{ __('ExportWizardDescription') }}
            </p>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
            <p class="text-blue-800 dark:text-blue-200">
                {{ __('ExportWizardNote') }}
            </p>
        </div>

        <div class="mt-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('SelectDataToExport') }}
            </p>
        </div>
    </div>
</div>
@endsection
