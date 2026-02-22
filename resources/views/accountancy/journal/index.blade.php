@extends('layouts.app')

@section('title', 'Accounting Journals')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Accounting Journals
        </h1>

        <x-card title="Journal Types">
            <x-table :columns="['Journal Type']">
                @php
                    // Default journals if not set
                    $journals = $journals ?? [];
                @endphp
                @forelse($journals as $journal)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">
                            <a href="{{ $journal['url'] ?? '#' }}" 
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                {{ $journal['name'] ?? 'Unknown' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No journals available
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>
@endsection
