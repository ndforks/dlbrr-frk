@extends('layouts.app')

@section('title', 'Edit Page Source - ' . ($website->ref ?? 'Website'))

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            Edit Page Source
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            {{ $page->title ?? 'Untitled Page' }}
        </p>

        <x-card title="HTML Source Editor">
            <form method="POST" action="" class="space-y-4">
                @csrf
                <input type="hidden" name="action" value="updatesource">
                <input type="hidden" name="websiteid" value="{{ $website->id ?? '' }}">
                <input type="hidden" name="pageid" value="{{ $page->id ?? '' }}">
                
                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        HTML Source Code
                    </label>
                    <textarea 
                        id="source"
                        name="PAGE_CONTENT" 
                        rows="30"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white font-mono text-sm"
                    >{{ $page->content ?? '' }}</textarea>
                </div>
                
                <div class="flex gap-2">
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out"
                    >
                        Update Source
                    </button>
                    <x-button href="?pageid={{ $page->id ?? '' }}&websiteid={{ $website->id ?? '' }}" variant="secondary">
                        Cancel
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
