@extends('layouts.app')

@section('title', 'Create New Position')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Create New Position
        </h1>

        <x-card title="Position Information">
            <form method="POST" action="{{ $_SERVER['PHP_SELF'] }}" class="space-y-6">
                @csrf
                <input type="hidden" name="action" value="add">
                @if($backtopage ?? false)
                    <input type="hidden" name="backtopage" value="{{ $backtopage }}">
                @endif
                @if($backtopageforcancel ?? false)
                    <input type="hidden" name="backtopageforcancel" value="{{ $backtopageforcancel }}">
                @endif
                @if(($fk_job ?? 0) > 0)
                    <input type="hidden" name="fk_job" value="{{ $fk_job }}">
                @endif

                @php
                    // Include common fields
                    if (defined('DOL_DOCUMENT_ROOT')) {
                        include DOL_DOCUMENT_ROOT.'/core/tpl/commonfields_add.tpl.php';
                        include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_add.tpl.php';
                    }
                @endphp

                <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out"
                    >
                        Create Position
                    </button>
                    <x-button href="{{ $backtopage ?? url('/hrm') }}" variant="secondary">
                        Cancel
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
