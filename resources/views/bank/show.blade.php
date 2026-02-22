@extends('layouts.app')

@section('title', 'Bank Account Details')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Bank Account: {{ $object->ref ?? 'New Account' }}
            </h1>
            <div class="flex gap-2">
                @if(isset($object->id) && $object->id > 0)
                    <x-button href="{{ url('/compta/bank/card.php?action=edit&id=' . $object->id) }}" variant="primary">
                        Edit
                    </x-button>
                @endif
                <x-button href="{{ url('/compta/bank/list.php') }}" variant="secondary">
                    Back to List
                </x-button>
            </div>
        </div>

        <x-card title="Account Information">
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
                            Bank Name
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $object->bank ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Account Number
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $object->account_number ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            IBAN
                        </label>
                        <p class="text-gray-900 dark:text-gray-100 font-mono">{{ $object->iban ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            BIC/SWIFT
                        </label>
                        <p class="text-gray-900 dark:text-gray-100 font-mono">{{ $object->bic ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Currency
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $object->currency_code ?? 'EUR' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status
                        </label>
                        @if($object->clos ?? 0)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Closed
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Open
                            </span>
                        @endif
                    </div>
                    
                    @if($object->owner_name ?? '')
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Account Owner
                            </label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $object->owner_name }}</p>
                            @if($object->owner_address ?? '')
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $object->owner_address }}</p>
                            @endif
                        </div>
                    @endif
                    
                    @if($object->comment ?? '')
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Comments
                            </label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $object->comment }}</p>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400">No account data available.</p>
            @endif
        </x-card>
    </div>
@endsection
