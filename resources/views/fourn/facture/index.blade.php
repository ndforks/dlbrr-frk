@extends('layouts.app')

@section('title', $title ?? 'Supplier Invoices')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $title ?? 'Supplier Invoices' }}
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <x-card title="Invoices Overview">
                    <p class="text-gray-700 dark:text-gray-300">
                        Supplier invoices list and statistics will be displayed here.
                    </p>
                </x-card>
            </div>

            <x-card title="Quick Actions">
                <div class="space-y-2">
                    <x-button href="{{ url('/fourn/facture/create') }}" variant="primary" class="w-full">
                        Create Invoice
                    </x-button>
                    <x-button href="{{ url('/fourn') }}" variant="secondary" class="w-full">
                        Back to Suppliers
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>
@endsection
