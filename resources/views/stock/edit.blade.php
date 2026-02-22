@extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Edit Warehouse
        </h1>

        <x-card title="Warehouse Information">
            <form method="POST" action="{{ url('/product/stock/card.php') }}" class="space-y-6">
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
                        <label for="lieu" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Location Summary
                        </label>
                        <input type="text" 
                               id="lieu" 
                               name="lieu" 
                               value="{{ $object->lieu ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        <select id="statut" 
                                name="statut" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="0" {{ ($object->statut ?? 1) == 0 ? 'selected' : '' }}>Closed</option>
                            <option value="1" {{ ($object->statut ?? 1) == 1 ? 'selected' : '' }}>Open</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Address
                        </label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               value="{{ $object->address ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="zip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Zip Code
                        </label>
                        <input type="text" 
                               id="zip" 
                               name="zip" 
                               value="{{ $object->zip ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="town" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Town
                        </label>
                        <input type="text" 
                               id="town" 
                               name="town" 
                               value="{{ $object->town ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phone
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ $object->phone ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="fax" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fax
                        </label>
                        <input type="text" 
                               id="fax" 
                               name="fax" 
                               value="{{ $object->fax ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">{{ $object->description ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                        Save Changes
                    </button>
                    <x-button href="{{ url('/product/stock/card.php?id=' . ($object->id ?? '')) }}" variant="secondary">
                        Cancel
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
