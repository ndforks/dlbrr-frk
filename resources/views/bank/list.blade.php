@extends('layouts.app')

@section('title', 'Bank Accounts')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Bank Accounts
        </h1>

        <x-card title="Bank Accounts List">
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                List of all bank accounts and financial accounts.
            </p>
            @php
                // Include legacy module logic
                if (function_exists('base_path')) {
                    require base_path('app/Modules/Compta/Bank/list.php');
                }
            @endphp
        </x-card>
    </div>
@endsection
