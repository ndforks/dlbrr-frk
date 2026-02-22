@extends('layouts.app')

@section('title', 'Warehouses / Stock')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Warehouses / Stock
            </h1>
            @if($user->hasRight('stock', 'creer'))
                <x-button href="{{ url('/product/stock/card.php?action=create') }}" variant="primary">
                    {{ $langs->trans('NewWarehouse') }}
                </x-button>
            @endif
        </div>

        <x-card title="{{ $langs->trans('ListOfWarehouses') }}">
            @if($warehouses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-blue-600 dark:bg-blue-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Ref') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Label') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('LocationSummary') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Town') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($warehouses as $warehouse)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ url('/product/stock/card.php?id=' . $warehouse->rowid) }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                            {{ $warehouse->ref }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $warehouse->label }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $warehouse->lieu }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $warehouse->town }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        @if($warehouse->statut == 1)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $langs->trans('Open') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ $langs->trans('Closed') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600 dark:text-gray-400">{{ $langs->trans('NoWarehousesDefined') }}</p>
                    @if($user->hasRight('stock', 'creer'))
                        <x-button href="{{ url('/product/stock/card.php?action=create') }}" variant="primary" class="mt-4">
                            {{ $langs->trans('CreateWarehouse') }}
                        </x-button>
                    @endif
                </div>
            @endif
        </x-card>
    </div>
@endsection
