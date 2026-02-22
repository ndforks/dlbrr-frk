@extends('layouts.app')

@section('title', __('CronInfo'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('CronInfo') }} - {{ $object->label }}
            </h1>
            <a href="{{ route('cron.list') }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                {{ __('BackToList') }}
            </a>
        </div>

        <div class="space-y-6">
            {{-- Basic Information --}}
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Information') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('DateCreation') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            @if($object->date_creation)
                                {{ date('Y-m-d H:i:s', $object->date_creation) }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('DateModification') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            @if($object->date_update)
                                {{ date('Y-m-d H:i:s', $object->date_update) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Execution Results --}}
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('ExecutionResults') }}
                </h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronLastResult') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            @if($object->lastresult !== '' && $object->lastresult !== null)
                                <span class="{{ $object->lastresult != 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    {{ $object->lastresult }}
                                </span>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">{{ __('CronNotYetRan') }}</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronLastOutput') }}</h3>
                        <div class="mt-1">
                            @if($object->lastoutput)
                                <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded text-sm text-gray-900 dark:text-gray-100 overflow-x-auto">{{ $object->lastoutput }}</pre>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">{{ __('None') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Execution History --}}
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('ExecutionHistory') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronDtLastLaunch') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            @if($object->datelastrun)
                                {{ date('Y-m-d H:i:s', $object->datelastrun) }}
                            @else
                                <span class="text-gray-500 dark:text-gray-400">{{ __('CronNotYetRan') }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronDtLastResult') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            @if($object->datelastresult)
                                {{ date('Y-m-d H:i:s', $object->datelastresult) }}
                            @else
                                <span class="text-gray-500 dark:text-gray-400">{{ __('CronNotYetRan') }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronNbRun') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $object->nbrun ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('cron.card', ['id' => $object->id]) }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                {{ __('BackToCard') }}
            </a>
        </div>
    </div>
</div>
@endsection
