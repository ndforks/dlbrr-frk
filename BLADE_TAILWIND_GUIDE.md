# Blade and Tailwind CSS Usage Guide

This document describes how to use Blade templating and Tailwind CSS in the Dolibarr Laravel project.

## Overview

The project uses:
- **Laravel 11** with Blade templating engine
- **Tailwind CSS v4** for styling (configured via Vite)
- **Blade Components** for reusable UI elements

## Tailwind CSS Configuration

### Vite Configuration
Tailwind CSS is configured in `vite.config.js`:
```javascript
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

### CSS Configuration
The main CSS file at `resources/css/app.css` imports Tailwind:
```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';
```

## Base Layout

All views should extend the base layout at `resources/views/layouts/app.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Page Title')

@section('content')
    <!-- Your content here -->
@endsection
```

The base layout includes:
- Responsive meta tags
- CSRF token
- Tailwind CSS via Vite
- Dark mode support
- Font configuration (Instrument Sans)

## Blade Components

### Card Component (`x-card`)

A styled container with optional title:

```blade
<x-card title="Card Title">
    Content goes here
</x-card>
```

### Button Component (`x-button`)

A styled link button with variants:

```blade
<!-- Primary button (default) -->
<x-button href="/some-url">Click Me</x-button>

<!-- Secondary button -->
<x-button href="/some-url" variant="secondary">Cancel</x-button>

<!-- Danger button -->
<x-button href="/some-url" variant="danger">Delete</x-button>
```

### Table Component (`x-table`)

A styled table with headers:

```blade
<x-table :columns="['ID', 'Name', 'Email']">
    @foreach($items as $item)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-6 py-4">{{ $item->id }}</td>
            <td class="px-6 py-4">{{ $item->name }}</td>
            <td class="px-6 py-4">{{ $item->email }}</td>
        </tr>
    @endforeach
</x-table>
```

## Common Tailwind CSS Patterns

### Container
```blade
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Content -->
</div>
```

### Form Input
```blade
<input 
    type="text" 
    name="field_name" 
    placeholder="Enter value..."
    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
>
```

### Button
```blade
<button 
    type="submit"
    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out"
>
    Submit
</button>
```

### Card/Box
```blade
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <!-- Content -->
</div>
```

### Table
```blade
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-blue-600 dark:bg-blue-800">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Header
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                    Data
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

## Dark Mode Support

All components and layouts support dark mode using Tailwind's `dark:` prefix:

```blade
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
    Content that works in both light and dark mode
</div>
```

## Responsive Design

Use Tailwind's responsive prefixes for mobile-first design:

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Responsive grid: 1 column on mobile, 2 on tablet, 3 on desktop -->
</div>
```

## Migration from Legacy Templates

### From .tpl.php to .blade.php

1. **Remove PHP opening/closing tags** - Blade uses `@php` and `@endphp` for PHP blocks
2. **Use Blade directives** instead of PHP control structures:
   - `<?php if (...) ?>` → `@if(...)`
   - `<?php foreach (...) ?>` → `@foreach(...)`
   - `<?php echo $var ?>` → `{{ $var }}`
3. **Add Tailwind CSS classes** instead of inline styles or custom CSS
4. **Use Blade components** for common UI elements

### Example Conversion

**Before (.tpl.php):**
```php
<?php if ($items) { ?>
    <table class="noborder">
        <?php foreach ($items as $item) { ?>
            <tr>
                <td><?php echo $item->name; ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>
```

**After (.blade.php):**
```blade
@if($items->count() > 0)
    <x-table :columns="['Name']">
        @foreach($items as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4">{{ $item->name }}</td>
            </tr>
        @endforeach
    </x-table>
@endif
```

## Building Assets

To compile Tailwind CSS:

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build
```

## Best Practices

1. **Always extend the base layout** (`layouts/app.blade.php`)
2. **Use Blade components** for reusable elements
3. **Follow Tailwind's utility-first approach** instead of custom CSS
4. **Support dark mode** by adding `dark:` variants
5. **Make designs responsive** using responsive prefixes (sm:, md:, lg:, xl:)
6. **Keep views clean** - move complex logic to controllers
7. **Use semantic HTML** with appropriate ARIA attributes for accessibility

## Example: Complete View

```blade
@extends('layouts.app')

@section('title', 'User List')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Users
        </h1>

        <x-card title="Search Users">
            <form method="GET" action="{{ url('/users') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search..."
                        value="{{ request('search') }}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                </div>
                <div class="mt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                        Search
                    </button>
                    <x-button href="{{ url('/users') }}" variant="secondary">
                        Reset
                    </x-button>
                </div>
            </form>
        </x-card>

        <div class="mt-6">
            <x-table :columns="['ID', 'Name', 'Email', 'Actions']">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">{{ $user->id }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <x-button href="{{ url('/users/' . $user->id) }}" variant="primary">
                                View
                            </x-button>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </div>
    </div>
@endsection
```

## Resources

- [Laravel Blade Documentation](https://laravel.com/docs/11.x/blade)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Tailwind CSS v4 Documentation](https://tailwindcss.com/docs/v4-beta)
