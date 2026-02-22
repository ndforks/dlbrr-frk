@extends('layouts.app')

@section('title', ($page->title ?? 'Website Page') . ' - ' . ($website->ref ?? 'Website'))

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            {{ $page->title ?? 'Untitled Page' }}
        </h1>
        
        <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
            <span class="font-medium">Website:</span> {{ $website->ref ?? 'N/A' }} | 
            <span class="font-medium">Page URL:</span> {{ $page->pageurl ?? 'N/A' }}
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <x-card title="Page Information">
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Description:</dt>
                        <dd class="text-gray-600 dark:text-gray-400 mt-1">{{ $page->description ?? 'No description' }}</dd>
                    </div>
                </dl>
            </x-card>

            <div class="lg:col-span-2">
                <x-card title="Quick Actions">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <x-button href="?action=editmeta" variant="primary" class="w-full">
                            Edit Metadata
                        </x-button>
                        <x-button href="?action=editcontent" variant="primary" class="w-full">
                            Edit Content
                        </x-button>
                        <x-button href="?action=editsource" variant="primary" class="w-full">
                            Edit Source
                        </x-button>
                        <x-button href="?action=setashome" variant="secondary" class="w-full">
                            Set as Home
                        </x-button>
                        <x-button href="?action=clone" variant="secondary" class="w-full">
                            Clone Page
                        </x-button>
                    </div>
                </x-card>
            </div>
        </div>

        <x-card title="Page Content">
            <div class="prose dark:prose-invert max-w-none">
                {!! $page->content ?? '<p class="text-gray-500 dark:text-gray-400">No content available</p>' !!}
            </div>
        </x-card>
    </div>
@endsection
