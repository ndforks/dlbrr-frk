@extends('layouts.app')

@section('title', 'Create Bank Account')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Create Bank Account
        </h1>

        <x-card title="Account Information">
            <form method="POST" action="{{ url('/compta/bank/card.php') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="action" value="add">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ref" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reference <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="ref" 
                               name="ref" 
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
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Number
                        </label>
                        <input type="text" 
                               id="account_number" 
                               name="account_number" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="code_banque" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bank Code
                        </label>
                        <input type="text" 
                               id="code_banque" 
                               name="code_banque" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="code_guichet" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Desk Code
                        </label>
                        <input type="text" 
                               id="code_guichet" 
                               name="code_guichet" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Number (National)
                        </label>
                        <input type="text" 
                               id="number" 
                               name="number" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="cle_rib" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Key
                        </label>
                        <input type="text" 
                               id="cle_rib" 
                               name="cle_rib" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="iban" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            IBAN
                        </label>
                        <input type="text" 
                               id="iban" 
                               name="iban" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white font-mono">
                    </div>
                    
                    <div>
                        <label for="bic" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            BIC/SWIFT
                        </label>
                        <input type="text" 
                               id="bic" 
                               name="bic" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white font-mono">
                    </div>
                    
                    <div>
                        <label for="account_currency_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Currency
                        </label>
                        <input type="text" 
                               id="account_currency_code" 
                               name="account_currency_code" 
                               value="EUR"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Type
                        </label>
                        <select id="type" 
                                name="type" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="0">Current Account</option>
                            <option value="1">Savings Account</option>
                            <option value="2">Cash Account</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="proprio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Owner
                        </label>
                        <input type="text" 
                               id="proprio" 
                               name="proprio" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="owner_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Owner Address
                        </label>
                        <textarea id="owner_address" 
                                  name="owner_address" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="account_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Comments
                        </label>
                        <textarea id="account_comment" 
                                  name="account_comment" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                        Create Account
                    </button>
                    <x-button href="{{ url('/compta/bank/list.php') }}" variant="secondary">
                        Cancel
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
