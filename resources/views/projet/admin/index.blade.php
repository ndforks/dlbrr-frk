@extends('layouts.app')

@section('title', __('Project Administration'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
            {{ __('Project Administration') }}
        </h1>
        <p class="text-gray-700 dark:text-gray-300">
            {{ __('Module options for projects will be managed from this page.') }}
        </p>
    </div>
</div>
@endsection
