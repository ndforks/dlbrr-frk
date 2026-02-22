@extends('layouts.app')

@section('title', 'Create Warehouse')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Create Warehouse
        </h1>

        <x-card title="Warehouse Information">
            @php
                // Include legacy module logic
                if (function_exists('base_path')) {
                    require base_path('app/Modules/Product/Stock/card.php');
                }
            @endphp
        </x-card>
    </div>
@endsection
