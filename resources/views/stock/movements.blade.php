@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Stock Movements
        </h1>

        <x-card title="{{ $langs->trans('StockMovements') }}">
            @if($movements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-blue-600 dark:bg-blue-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Date') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Product') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Warehouse') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Qty') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Type') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Label') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Author') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($movements as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $movement->datem ? $movement->datem->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($movement->product)
                                            <a href="{{ url('/product/card.php?id=' . $movement->fk_product) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $movement->product->ref ?? '' }} - {{ $movement->product->label ?? '' }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($movement->entrepot)
                                            <a href="{{ url('/product/stock/card.php?id=' . $movement->fk_entrepot) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $movement->entrepot->ref ?? '' }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        @if($movement->value > 0)
                                            <span class="text-green-600 dark:text-green-400 font-medium">+{{ $movement->value }}</span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400 font-medium">{{ $movement->value }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @if($movement->type_mouvement == 0)
                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded">{{ $langs->trans('StockIncrease') }}</span>
                                        @elseif($movement->type_mouvement == 1)
                                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded">{{ $langs->trans('StockDecrease') }}</span>
                                        @elseif($movement->type_mouvement == 2)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded">{{ $langs->trans('StockTransfer') }}</span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded">{{ $langs->trans('Other') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $movement->label ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @if($movement->author)
                                            {{ $movement->author->firstname ?? '' }} {{ $movement->author->lastname ?? '' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600 dark:text-gray-400">{{ $langs->trans('NoStockMovements') }}</p>
                </div>
            @endif
        </x-card>
    </div>
@endsection
