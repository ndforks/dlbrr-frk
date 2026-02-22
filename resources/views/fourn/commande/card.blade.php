@extends('layouts.app')

@section('title', 'Supplier Order')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Supplier Order Card
        </h1>

        <x-card title="Order Details">
            <p class="text-gray-700 dark:text-gray-300">
                Supplier order details will be displayed here.
            </p>
        </x-card>

        <div class="mt-6 flex gap-2">
            <x-button href="{{ url('/fourn/commande') }}" variant="secondary">
                Back to Orders
            </x-button>
        </div>
    </div>
@endsection
