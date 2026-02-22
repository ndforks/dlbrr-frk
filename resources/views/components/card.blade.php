@props([
    'title' => '',
])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
    @if($title)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
