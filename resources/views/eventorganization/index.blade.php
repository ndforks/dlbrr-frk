@extends('layouts.app')

@section('title', 'Event Organization')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Event Organization Area
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-card title="Conferences & Booths">
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Manage event conferences and booth registrations.
                </p>
                <x-button href="{{ url('/eventorganization/conferenceorbooth') }}" variant="primary">
                    View Conferences & Booths
                </x-button>
            </x-card>

            <x-card title="Quick Actions">
                <div class="space-y-2">
                    <x-button href="{{ url('/eventorganization/conferenceorbooth/create') }}" variant="primary" class="w-full">
                        Create Conference/Booth
                    </x-button>
                    <x-button href="{{ url('/eventorganization/list') }}" variant="secondary" class="w-full">
                        View All Events
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>
@endsection
