@props([
    'href' => '#',
    'variant' => 'primary', // primary, secondary, danger
])

@php
    $classes = match($variant) {
        'primary' => 'px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out',
        'secondary' => 'px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium rounded-md transition duration-150 ease-in-out',
        'danger' => 'px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition duration-150 ease-in-out',
        default => 'px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out',
    };
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
