@extends('layouts.app')

@section('title', 'Warehouse Details')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Warehouse: {{ $object->ref ?? 'New Warehouse' }}
            </h1>
            <div class="flex gap-2">
                @if(isset($object->id) && $object->id > 0)
                    <x-button href="{{ url('/product/stock/card.php?action=edit&id=' . $object->id) }}" variant="primary">
                        Edit
                    </x-button>
                @endif
                <x-button href="{{ url('/product/stock/list.php') }}" variant="secondary">
                    Back to List
                </x-button>
            </div>
        </div>

        <x-card title="Warehouse Information">
            @if(isset($object->id) && $object->id > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Reference
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $object->ref }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Label
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $object->label }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Location
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $object->lieu ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status
                        </label>
                        @if($object->statut == 1)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Open
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Closed
                            </span>
                        @endif
                    </div>
                    
                    @if($object->address ?? '')
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Address
                            </label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $object->address }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Zip Code
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $object->zip ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Town
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $object->town ?? '-' }}</p>
                    </div>
                    
                    @if($object->phone ?? '')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Phone
                            </label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $object->phone }}</p>
                        </div>
                    @endif
                    
                    @if($object->fax ?? '')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Fax
                            </label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $object->fax }}</p>
                        </div>
                    @endif
                    
                    @if($object->description ?? '')
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Description
                            </label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $object->description }}</p>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400">No warehouse data available.</p>
            @endif
        </x-card>
    </div>
@endsection
