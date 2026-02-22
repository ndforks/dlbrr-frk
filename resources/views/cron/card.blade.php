@extends('layouts.app')

@section('title', __('CronTask'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('CronTask') }} 
                @if($object->id)
                    #{{ $object->id }}
                @endif
            </h1>
            <a href="{{ route('cron.list') }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                {{ __('BackToList') }}
            </a>
        </div>

        @if($action === 'create' || $action === 'edit')
            {{-- Create/Edit Form --}}
            <form action="{{ route('cron.card') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="action" value="{{ $action === 'edit' ? 'update' : 'add' }}">
                @if($object->id)
                    <input type="hidden" name="id" value="{{ $object->id }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('CronLabel') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="label" value="{{ $object->label }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('CronType') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="jobtype" id="jobtype" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="method" {{ $object->jobtype === 'method' ? 'selected' : '' }}>{{ __('Method') }}</option>
                            <option value="command" {{ $object->jobtype === 'command' ? 'selected' : '' }}>{{ __('Command') }}</option>
                        </select>
                    </div>

                    <div class="blockmethod">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('CronModule') }}
                        </label>
                        <input type="text" name="module_name" value="{{ $object->module_name }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <div class="blockmethod">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('CronClassFile') }}
                        </label>
                        <input type="text" name="classesname" value="{{ $object->classesname }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <div class="blockmethod">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('CronMethod') }}
                        </label>
                        <input type="text" name="methodename" value="{{ $object->methodename }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <div class="blockcommand">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('CronCommand') }}
                        </label>
                        <input type="text" name="command" value="{{ $object->command }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('CronEvery') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-4">
                            <select name="nbfrequency" class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-100">
                                @for($i = 1; $i <= 60; $i++)
                                    <option value="{{ $i }}" {{ $object->frequency == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <label class="flex items-center">
                                <input type="radio" name="unitfrequency" value="60" {{ $object->unitfrequency == '60' ? 'checked' : '' }} class="mr-2">
                                {{ __('Minutes') }}
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="unitfrequency" value="3600" {{ $object->unitfrequency == '3600' ? 'checked' : '' }} class="mr-2">
                                {{ __('Hours') }}
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="unitfrequency" value="86400" {{ $object->unitfrequency == '86400' ? 'checked' : '' }} class="mr-2">
                                {{ __('Days') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('cron.list') }}" 
                       class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        @else
            {{-- View Mode --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronLabel') }}</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $object->label }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronType') }}</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $object->jobtype }}</p>
                </div>

                @if($object->jobtype === 'method')
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronModule') }}</h3>
                        <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $object->module_name }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronMethod') }}</h3>
                        <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $object->methodename }}</p>
                    </div>
                @else
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CronCommand') }}</h3>
                        <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $object->command }}</p>
                    </div>
                @endif

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</h3>
                    <p class="mt-1">
                        @if($object->status)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 rounded">
                                {{ __('Active') }}
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded">
                                {{ __('Inactive') }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('cron.card', ['id' => $object->id, 'action' => 'edit']) }}" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                    {{ __('Edit') }}
                </a>

                @if($permissiontoexecute && $object->status)
                    <a href="{{ route('cron.card', ['id' => $object->id, 'action' => 'execute']) }}" 
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                        {{ __('CronExecute') }}
                    </a>
                @endif

                @if($object->status)
                    <a href="{{ route('cron.card', ['id' => $object->id, 'action' => 'inactive']) }}" 
                       class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md transition-colors">
                        {{ __('CronStatusInactiveBtn') }}
                    </a>
                @else
                    <a href="{{ route('cron.card', ['id' => $object->id, 'action' => 'activate']) }}" 
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                        {{ __('CronStatusActiveBtn') }}
                    </a>
                @endif

                @if($permissiontodelete)
                    <a href="{{ route('cron.card', ['id' => $object->id, 'action' => 'delete']) }}" 
                       class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors"
                       onclick="return confirm('{{ __('CronConfirmDelete') }}')">
                        {{ __('Delete') }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
