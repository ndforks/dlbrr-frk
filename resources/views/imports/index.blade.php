@extends('layouts.app')

@section('title', __($title))

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-6">{{ __($title) }}</h1>

    @if(isModEnabled('import') && $usercanimport)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">{{ __('ImportArea') }}</h2>
        
        <div class="mb-4">
            <h3 class="text-lg font-medium mb-2">{{ __('AvailableFormats') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Format') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('LibraryShort') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('LibraryVersion') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($importFormats as $format)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    {!! img_picto_common($format['label'], $format['picto']) !!}
                                    <span class="ml-2">{{ $format['label'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $format['lib'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">{{ $format['version'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if(count($import->array_import_code) > 0)
        <div class="text-center mt-6">
            <a href="{{ route('imports.wizard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                <i class="fa fa-plus-circle mr-2"></i>
                {{ __('NewImport') }}
            </a>
        </div>
        @endif
    </div>
    @endif

    @if(isModEnabled('export') && $usercanexport)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">{{ __('ExportsArea') }}</h2>
        
        <div class="mb-4">
            <h3 class="text-lg font-medium mb-2">{{ __('AvailableFormats') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Format') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('LibraryShort') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('LibraryVersion') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($exportFormats as $format)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    {!! img_picto_common($format['label'], $format['picto']) !!}
                                    <span class="ml-2">{{ $format['label'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $format['lib'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">{{ $format['version'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if(count($export->array_export_code) > 0)
        <div class="text-center mt-6">
            <a href="{{ route('exports.wizard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                <i class="fa fa-plus-circle mr-2"></i>
                {{ __('NewExport') }}
            </a>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
