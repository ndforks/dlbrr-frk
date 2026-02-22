@extends('layouts.app')

@section('title', 'Edit Page Metadata - ' . ($website->ref ?? 'Website'))

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Edit Page Metadata
        </h1>

        <x-card title="Page Metadata">
            <form method="POST" action="" class="space-y-6">
                @csrf
                <input type="hidden" name="action" value="updatemeta">
                <input type="hidden" name="websiteid" value="{{ $website->id ?? '' }}">
                <input type="hidden" name="pageid" value="{{ $page->id ?? '' }}">
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Page Title
                    </label>
                    <input 
                        type="text" 
                        id="title"
                        name="WEBSITE_TITLE" 
                        value="{{ $page->title ?? '' }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                </div>
                
                <div>
                    <label for="pageurl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Page URL
                    </label>
                    <input 
                        type="text" 
                        id="pageurl"
                        name="WEBSITE_PAGENAME" 
                        value="{{ $page->pageurl ?? '' }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea 
                        id="description"
                        name="WEBSITE_DESCRIPTION" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >{{ $page->description ?? '' }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keywords
                        </label>
                        <input 
                            type="text" 
                            id="keywords"
                            name="WEBSITE_KEYWORDS" 
                            value="{{ $page->keywords ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                    
                    <div>
                        <label for="lang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Language
                        </label>
                        <input 
                            type="text" 
                            id="lang"
                            name="WEBSITE_LANG" 
                            value="{{ $page->lang ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                </div>
                
                <div>
                    <label for="alias" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Alternative Alias
                    </label>
                    <input 
                        type="text" 
                        id="alias"
                        name="WEBSITE_ALIASALT" 
                        value="{{ $page->aliasalt ?? '' }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                </div>
                
                <div class="flex gap-2">
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out"
                    >
                        Update Metadata
                    </button>
                    <x-button href="?pageid={{ $page->id ?? '' }}&websiteid={{ $website->id ?? '' }}" variant="secondary">
                        Cancel
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
