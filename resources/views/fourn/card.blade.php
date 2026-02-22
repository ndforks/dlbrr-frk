@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Supplier Card
        </h1>

        <x-card title="Supplier Information">
            <div class="space-y-4">
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Supplier ID:</span>
                    <span class="text-gray-900 dark:text-gray-100 ml-2">{{ $id ?? 'N/A' }}</span>
                </div>
                <p class="text-gray-600 dark:text-gray-400">
                    Supplier details will be displayed here.
                </p>
            </div>
        </x-card>

        <div class="mt-6 flex gap-2">
            <x-button href="{{ url('/fourn') }}" variant="secondary">
                Back to List
            </x-button>
        </div>
    </div>
@endsection
