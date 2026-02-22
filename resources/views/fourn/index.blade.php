@extends('layouts.app')

@section('title', $title ?? 'Suppliers')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $title ?? 'Suppliers Area' }}
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-card title="Supplier Orders">
                <div class="space-y-4">
                    <p class="text-gray-700 dark:text-gray-300">
                        Manage supplier orders and purchase orders.
                    </p>
                    @php
                        // Display order statistics if available
                        $orderStats = $orderStats ?? [];
                    @endphp
                    @if(!empty($orderStats))
                        <div class="space-y-2">
                            @foreach($orderStats as $stat)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">{{ $stat['label'] ?? 'Unknown' }}</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $stat['count'] ?? 0 }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <x-button href="{{ url('/fourn/commande') }}" variant="primary" class="w-full mt-4">
                        View Orders
                    </x-button>
                </div>
            </x-card>

            <x-card title="Supplier Invoices">
                <div class="space-y-4">
                    <p class="text-gray-700 dark:text-gray-300">
                        Manage supplier invoices and payments.
                    </p>
                    <x-button href="{{ url('/fourn/facture') }}" variant="primary" class="w-full">
                        View Invoices
                    </x-button>
                </div>
            </x-card>

            <x-card title="Quick Actions">
                <div class="space-y-2">
                    <x-button href="{{ url('/societe/card.php?type=f&action=create') }}" variant="primary" class="w-full">
                        New Supplier
                    </x-button>
                    <x-button href="{{ url('/fourn/commande/card.php?action=create') }}" variant="secondary" class="w-full">
                        New Order
                    </x-button>
                    <x-button href="{{ url('/fourn/facture/card.php?action=create') }}" variant="secondary" class="w-full">
                        New Invoice
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>
@endsection
