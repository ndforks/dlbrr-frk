@extends('layouts.app')

@section('title', 'Contacts / Addresses')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Contacts / Addresses</h1>
        
        <!-- Search Form -->
        <form method="GET" action="{{ url('/contact/list.php') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <input type="text" name="search_all" placeholder="Search all..." 
                       value="{{ $search['all'] ?? '' }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <input type="text" name="search_lastname" placeholder="Last name" 
                       value="{{ $search['lastname'] ?? '' }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <input type="text" name="search_firstname" placeholder="First name" 
                       value="{{ $search['firstname'] ?? '' }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <input type="text" name="search_societe" placeholder="Company" 
                       value="{{ $search['societe'] ?? '' }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <input type="text" name="search_email" placeholder="Email" 
                       value="{{ $search['email'] ?? '' }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <input type="text" name="search_phone" placeholder="Phone" 
                       value="{{ $search['phone'] ?? '' }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>
            <div class="mt-4 flex gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                    Search
                </button>
                <a href="{{ url('/contact/list.php') }}" 
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium rounded-md transition duration-150 ease-in-out">
                    Reset
                </a>
            </div>
        </form>
        
        <!-- Results Summary -->
        <div class="mb-4 text-gray-700 dark:text-gray-300">
            <strong>Total: {{ $total }} contacts</strong>
            @if($total > $limit)
                <span class="text-gray-600 dark:text-gray-400"> (Showing {{ $page * $limit + 1 }} to {{ min(($page + 1) * $limit, $total) }})</span>
            @endif
        </div>
        
        <!-- Contacts Table -->
        @if($contacts->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-blue-600 dark:bg-blue-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Last Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">First Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mobile</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Address</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($contacts as $contact)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $contact->rowid }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ url('/contact/card.php?id=' . $contact->rowid) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                        {{ $contact->lastname }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $contact->firstname }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($contact->societe)
                                        <a href="{{ url('/societe/card.php?id=' . $contact->societe->rowid) }}"
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $contact->societe->nom }}
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($contact->email)
                                        <a href="mailto:{{ $contact->email }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $contact->email }}
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $contact->phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $contact->phone_mobile }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $contact->address }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($total > $limit)
                <div class="mt-6 flex justify-center items-center gap-2">
                    @if($page > 0)
                        <a href="{{ url('/contact/list.php?page=' . ($page - 1) . '&' . http_build_query($search)) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                            Previous
                        </a>
                    @endif
                    
                    <span class="px-4 py-2 text-gray-700 dark:text-gray-300">
                        Page {{ $page + 1 }} of {{ ceil($total / $limit) }}
                    </span>
                    
                    @if(($page + 1) * $limit < $total)
                        <a href="{{ url('/contact/list.php?page=' . ($page + 1) . '&' . http_build_query($search)) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                            Next
                        </a>
                    @endif
                </div>
            @endif
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-600 dark:text-gray-400">No contacts found matching your search criteria.</p>
            </div>
        @endif
    </div>
@endsection
