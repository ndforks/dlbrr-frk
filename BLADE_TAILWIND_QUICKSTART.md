# Quick Start: Using Blade and Tailwind CSS

This is a quick reference guide for developers working with Blade templates and Tailwind CSS in this project.

## 📁 File Structure

```
resources/views/
├── layouts/
│   └── app.blade.php          # Base layout - extend this!
├── components/
│   ├── card.blade.php         # x-card component
│   ├── button.blade.php       # x-button component
│   └── table.blade.php        # x-table component
└── examples/
    └── blade-tailwind.blade.php  # See it in action
```

## 🚀 Quick Start

### 1. Create a New View

```blade
@extends('layouts.app')

@section('title', 'My Page')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            My Page Title
        </h1>
        
        <x-card title="My Card">
            Content goes here
        </x-card>
    </div>
@endsection
```

### 2. Use Components

```blade
<!-- Card -->
<x-card title="Optional Title">
    Your content here
</x-card>

<!-- Buttons -->
<x-button href="/path" variant="primary">Click Me</x-button>
<x-button href="/path" variant="secondary">Cancel</x-button>
<x-button href="/path" variant="danger">Delete</x-button>

<!-- Table -->
<x-table :columns="['ID', 'Name', 'Email']">
    @foreach($items as $item)
        <tr>
            <td class="px-6 py-4">{{ $item->id }}</td>
            <td class="px-6 py-4">{{ $item->name }}</td>
            <td class="px-6 py-4">{{ $item->email }}</td>
        </tr>
    @endforeach
</x-table>
```

## 🎨 Common Tailwind Patterns

### Container
```blade
<div class="container mx-auto px-4 py-8 max-w-7xl">
```

### Form Input
```blade
<input 
    type="text"
    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
>
```

### Button
```blade
<button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
    Submit
</button>
```

### Grid Layout
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Items -->
</div>
```

## 🌙 Dark Mode

Just add `dark:` prefix to any class:

```blade
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
    Works in both light and dark mode!
</div>
```

## 📱 Responsive Design

Use breakpoint prefixes:

```blade
<div class="text-sm md:text-base lg:text-lg">
    <!-- Small on mobile, medium on tablet, large on desktop -->
</div>
```

Breakpoints:
- `sm:` - 640px and up
- `md:` - 768px and up
- `lg:` - 1024px and up
- `xl:` - 1280px and up

## 🔨 Build Commands

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build
```

## 📖 Full Documentation

For complete documentation, see:
- **BLADE_TAILWIND_GUIDE.md** - Comprehensive guide with examples
- **VIEW_REFACTORING_SUMMARY.md** - Overview of the refactoring work
- **resources/views/examples/blade-tailwind.blade.php** - Live examples

## 💡 Tips

1. **Always extend the base layout**: `@extends('layouts.app')`
2. **Use components**: Don't repeat yourself - use x-card, x-button, x-table
3. **Dark mode**: Add `dark:` variants for all colors
4. **Mobile first**: Start with mobile styles, add `md:` and `lg:` for larger screens
5. **Semantic HTML**: Use proper heading levels, labels, and ARIA attributes

## ❓ Common Questions

**Q: Can I use custom CSS?**  
A: Prefer Tailwind utilities. If needed, add custom styles to `resources/css/app.css` or use `@push('styles')` in your view.

**Q: How do I add JavaScript?**  
A: Use `@push('scripts')` at the end of your view's content section.

**Q: What about old .tpl.php files?**  
A: Gradually migrate them to Blade. See BLADE_TAILWIND_GUIDE.md for migration examples.

**Q: Can I create my own components?**  
A: Yes! Add them to `resources/views/components/`. They automatically become available as `<x-component-name>`.

## 🎯 Example: Complete CRUD View

```blade
@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Users</h1>
            <x-button href="/users/create" variant="primary">Add User</x-button>
        </div>

        <x-card title="Search">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="search" placeholder="Search..." 
                       class="px-4 py-2 border rounded-md dark:bg-gray-700">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md">
                    Search
                </button>
            </form>
        </x-card>

        <div class="mt-6">
            <x-table :columns="['Name', 'Email', 'Actions']">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <x-button href="/users/{{ $user->id }}" variant="primary">
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

---

**Need Help?** Check the full documentation in BLADE_TAILWIND_GUIDE.md or look at examples/blade-tailwind.blade.php
