@extends('layouts.app')

@section('title', 'Edit Bank Account')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Edit Bank Account
        </h1>

        <x-card title="Account Information">
            @php
                // Include legacy module logic
                if (function_exists('base_path')) {
                    require base_path('app/Modules/Compta/Bank/card.php');
                }
            @endphp
        </x-card>
    </div>
@endsection
