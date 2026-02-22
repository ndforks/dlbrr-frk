@extends('layouts.app')

@section('title', 'Example: Blade & Tailwind CSS')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Blade & Tailwind CSS Example
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                This page demonstrates the proper use of Blade templating and Tailwind CSS styling.
            </p>
        </div>

        <!-- Example Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Using Card Component -->
            <x-card title="Card Component">
                <p class="text-gray-700 dark:text-gray-300">
                    This card uses the <code class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">x-card</code> component
                    with a title prop.
                </p>
            </x-card>

            <!-- Card 2: Statistics -->
            <x-card title="Statistics">
                <div class="space-y-4">
                    <div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">1,234</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Users</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">567</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Active Orders</div>
                    </div>
                </div>
            </x-card>

            <!-- Card 3: Actions -->
            <x-card title="Quick Actions">
                <div class="space-y-2">
                    <x-button href="#" variant="primary" class="w-full">
                        Primary Action
                    </x-button>
                    <x-button href="#" variant="secondary" class="w-full">
                        Secondary Action
                    </x-button>
                    <x-button href="#" variant="danger" class="w-full">
                        Delete Action
                    </x-button>
                </div>
            </x-card>
        </div>

        <!-- Form Example -->
        <x-card title="Form Example">
            <form method="POST" action="#" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Name
                        </label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            placeholder="Enter your name"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            placeholder="Enter your email"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                </div>

                <div class="flex gap-2">
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out"
                    >
                        Submit
                    </button>
                </div>
            </form>
        </x-card>

        <!-- Table Example -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                Table Example
            </h2>
            
            <x-table :columns="['ID', 'Name', 'Email', 'Status']">
                @for($i = 1; $i <= 3; $i++)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $i }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">User {{ $i }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">user{{ $i }}@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($i % 2 == 0)
                                <span class="px-3 py-1 text-xs font-medium text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200 rounded-full">Active</span>
                            @else
                                <span class="px-3 py-1 text-xs font-medium text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-full">Inactive</span>
                            @endif
                        </td>
                    </tr>
                @endfor
            </x-table>
        </div>
    </div>
@endsection
