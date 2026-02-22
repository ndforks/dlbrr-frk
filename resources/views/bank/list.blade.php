@extends('layouts.app')

@section('title', 'Bank Accounts')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Bank Accounts
        </h1>

        <x-card title="Bank Accounts List">
            @if($accounts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-blue-600 dark:bg-blue-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Ref') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Label') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Bank') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('AccountNumber') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Currency') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">{{ $langs->trans('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($accounts as $account)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ url('/compta/bank/card.php?id=' . $account->rowid) }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                            {{ $account->ref }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $account->label }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $account->bank }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $account->account_number }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $account->currency_code }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        @if($account->clos)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ $langs->trans('Closed') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $langs->trans('Open') }}
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
                    <p class="text-gray-600 dark:text-gray-400">{{ $langs->trans('NoRecordsFound') }}</p>
                </div>
            @endif
        </x-card>
    </div>
@endsection
