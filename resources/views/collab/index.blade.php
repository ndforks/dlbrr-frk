@extends('layouts.app')

@section('title', __('WebsiteSetup'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
            {{ __('WebsiteSetup') }}
        </h1>

        <form action="{{ route('collab.index') }}" method="POST" class="space-y-6">
            @csrf
            
            @if($action === 'create')
                <input type="hidden" name="action" value="add">
            @endif

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            {{ __('Collaborative Document Editing (PAD)') }}
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <p>{{ __('This is a placeholder page for collaborative document editing functionality.') }}</p>
                            <p class="mt-1">{{ __('Future features will include shared document editing capabilities.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.index') }}" 
                   class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                    {{ __('Back') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
