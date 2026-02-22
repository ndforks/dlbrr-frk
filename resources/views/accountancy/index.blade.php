@extends('layouts.app')

@section('title', 'Accountancy Area')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Accountancy Area
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                global $db, $langs, $user, $conf, $hookmanager, $mysoc;
                
                // Legacy accounting dashboard rendering
                // This would show accounting modules, reports, and quick actions
            @endphp
        </div>
    </div>
@endsection
