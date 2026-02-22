@extends('layouts.app')

@section('title', 'Edit Bank Account')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Edit Bank Account
        </h1>

        <x-card title="Account Information">
            <form method="POST" action="{{ url('/compta/bank/card.php') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="{{ $object->id ?? '' }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ref" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reference <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="ref" 
                               name="ref" 
                               value="{{ $object->ref ?? '' }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Label <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="label" 
                               name="label" 
                               value="{{ $object->label ?? '' }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bank Name
                        </label>
                        <input type="text" 
                               id="bank" 
                               name="bank" 
                               value="{{ $object->bank ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Number
                        </label>
                        <input type="text" 
                               id="account_number" 
                               name="account_number" 
                               value="{{ $object->account_number ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="code_banque" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bank Code
                        </label>
                        <input type="text" 
                               id="code_banque" 
                               name="code_banque" 
                               value="{{ $object->code_banque ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="code_guichet" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Desk Code
                        </label>
                        <input type="text" 
                               id="code_guichet" 
                               name="code_guichet" 
                               value="{{ $object->code_guichet ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Number (National)
                        </label>
                        <input type="text" 
                               id="number" 
                               name="number" 
                               value="{{ $object->number ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="cle_rib" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Key
                        </label>
                        <input type="text" 
                               id="cle_rib" 
                               name="cle_rib" 
                               value="{{ $object->cle_rib ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="iban" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            IBAN
                        </label>
                        <input type="text" 
                               id="iban" 
                               name="iban" 
                               value="{{ $object->iban ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white font-mono">
                    </div>
                    
                    <div>
                        <label for="bic" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            BIC/SWIFT
                        </label>
                        <input type="text" 
                               id="bic" 
                               name="bic" 
                               value="{{ $object->bic ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white font-mono">
                    </div>
                    
                    <div>
                        <label for="account_currency_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Currency
                        </label>
                        <input type="text" 
                               id="account_currency_code" 
                               name="account_currency_code" 
                               value="{{ $object->currency_code ?? 'EUR' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="clos" class="flex items-center space-x-2">
                            <input type="checkbox" 
                                   id="clos" 
                                   name="clos" 
                                   value="1"
                                   {{ ($object->clos ?? 0) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Account Closed</span>
                        </label>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="proprio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Owner
                        </label>
                        <input type="text" 
                               id="proprio" 
                               name="proprio" 
                               value="{{ $object->owner_name ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="owner_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Owner Address
                        </label>
                        <textarea id="owner_address" 
                                  name="owner_address" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">{{ $object->owner_address ?? '' }}</textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="account_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Comments
                        </label>
                        <textarea id="account_comment" 
                                  name="account_comment" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">{{ $object->comment ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                        Save Changes
                    </button>
                    <x-button href="{{ url('/compta/bank/card.php?id=' . ($object->id ?? '')) }}" variant="secondary">
                        Cancel
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
