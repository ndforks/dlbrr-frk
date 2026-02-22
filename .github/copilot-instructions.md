# GitHub Copilot Instructions for Dolibarr

This file provides instructions for GitHub Copilot to assist with development in the Dolibarr ERP & CRM repository.

## Project Overview

**This is a Laravel-integrated fork of Dolibarr ERP & CRM.** The project is actively being refactored from traditional PHP to modern Laravel patterns.

Dolibarr is an open-source ERP & CRM software designed for small, medium, and large companies, foundations, and freelancers. It provides comprehensive business management features including customer/supplier management, invoicing, inventory, projects, HR, and more.

- **License**: GPL-3.0-or-later
- **Primary Language**: PHP 8.2+
- **Framework**: Laravel 11
- **Homepage**: https://www.dolibarr.org
- **Wiki**: https://wiki.dolibarr.org

### Current Refactoring Status

This repository is undergoing a gradual migration to Laravel:
- ✅ ~65 controllers refactored to Laravel patterns
- ✅ Helper functions modernized (GETPOST → request(), etc.)
- ✅ Routes using Laravel conventions with named routes
- 🔄 Legacy Dolibarr modules in `app/Modules` being incrementally refactored
- See `LARAVEL_REFACTORING_PLAN.md` and `REFACTORING_PATTERN.md` for details

## Technology Stack

### Backend
- **PHP**: 8.2+ (required)
- **Framework**: Laravel 11
- **Databases**: MariaDB, MySQL, or PostgreSQL
- **ORM**: Eloquent (for refactored code) + legacy Dolibarr DB layer

### Frontend
- **Templating**: Blade (Laravel's templating engine)
- **CSS Framework**: Tailwind CSS v4
- **Build Tool**: Vite
- **JavaScript**: Vanilla JS and modern frameworks (Chart.js, etc.)
- **Font**: Instrument Sans

### APIs
- REST API (Laravel routes)
- Legacy SOAP API (in `app/Modules`)

## Project Structure

```
dlbrr-frk/
├── app/
│   ├── Http/
│   │   └── Controllers/    # Modern Laravel controllers (~65 refactored)
│   ├── Models/             # Eloquent models for database tables
│   ├── Modules/            # Legacy Dolibarr modules (being refactored)
│   ├── Providers/          # Laravel service providers
│   ├── Services/           # Business logic services
│   └── helpers.php         # Compatibility helpers for legacy code
├── resources/
│   ├── views/              # Blade templates
│   │   ├── layouts/        # Base layouts (app.blade.php)
│   │   ├── components/     # Reusable Blade components
│   │   └── [modules]/      # Module-specific views
│   ├── css/
│   │   └── app.css         # Tailwind CSS entry point
│   └── js/
│       └── app.js          # JavaScript entry point
├── routes/
│   ├── web.php             # Web routes (using named routes)
│   └── console.php         # Artisan commands
├── tests/
│   ├── Feature/            # Feature tests
│   └── Unit/               # Unit tests
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── public/                 # Public web root (compiled assets)
├── config/                 # Laravel configuration files
└── storage/                # Application storage (logs, cache, etc.)
```

**Note**: Legacy structure exists in `app/Modules` for backward compatibility.

## Development Environment Setup

### Requirements
- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm
- MariaDB/MySQL or PostgreSQL
- Git

### Getting Started
```bash
# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Build frontend assets
npm run build

# Start development server
php artisan serve
# In another terminal:
npm run dev
```

## Development Guidelines

### Code Style and Standards

1. **PHP Code Style**:
   - Follow PSR-1 and PSR-2 standards where possible
   - Use tabs for indentation (legacy files) or 4 spaces (new Laravel files)
   - Opening braces on same line for functions/methods
   - Clear variable and function naming
   - Use type hints and return types in new code

2. **File Headers**:
   - All PHP files must include copyright headers with GPL license information
   - Format: `/* Copyright (C) YEAR Author Name <email> */`

3. **Documentation**:
   - Use PHPDoc comments for classes, methods, and functions
   - Document parameters with `@param` and return values with `@return`
   - Add inline comments for complex logic

4. **Security**:
   - Always validate and sanitize user input
   - Use Laravel's request validation for new controllers
   - Use prepared statements for database queries (Eloquent preferred)
   - Include CSRF protection in forms (@csrf in Blade)
   - Follow security best practices documented in the wiki

### Code Quality Tools

- **PHPStan**: Static analysis tool (config: `phpstan.neon.dist`)
- **Phan**: Another static analysis tool (config in `.phan/`)
- **Pre-commit hooks**: Configured in `.pre-commit-config.yaml`

Run quality checks:
```bash
# PHPStan analysis
composer phpstan  # or direct phpstan command

# Phan analysis
phan --config-file .phan/config.php
```

### Coding Conventions

#### Laravel-Specific Conventions

1. **Controllers**:
   - Place in `app/Http/Controllers`
   - Use single action controllers with `__invoke()` when appropriate
   - Return views or redirects, not raw HTML
   - Use Laravel's request validation
   - Example:
   ```php
   class ListContacts extends Controller
   {
       public function __invoke(Request $request): View
       {
           $contacts = Contact::with('societe')->get();
           return view('contact.list', compact('contacts'));
       }
   }
   ```

2. **Models (Eloquent)**:
   - Place in `app/Models`
   - Extend `Illuminate\Database\Eloquent\Model`
   - Define `$table`, `$fillable`, `$casts` properties
   - Use relationships (hasMany, belongsTo, etc.)
   - Avoid raw SQL queries when possible

3. **Routes**:
   - Define in `routes/web.php`
   - Always use named routes: `->name('contact.list')`
   - No .php extensions in route URLs
   - Use route() helper in redirects: `redirect()->route('contact.list')`

4. **Request Handling**:
   - NEW CODE: Use Laravel's `$request->input()` or `$request->get()`
   - LEGACY: Helper functions like GETPOST() are in `app/helpers.php` for compatibility
   - Validation: Use `$request->validate()` in controllers

5. **Blade Templating**:
   - All views in `resources/views/` with `.blade.php` extension
   - Extend base layout: `@extends('layouts.app')`
   - Use sections: `@section('content')...@endsection`
   - Use components: `<x-card>`, `<x-button>` (see BLADE_TAILWIND_GUIDE.md)
   - CSRF token: `@csrf` directive in forms
   - Example:
   ```blade
   @extends('layouts.app')
   
   @section('title', 'Contact List')
   
   @section('content')
       <x-card title="Contacts">
           @foreach($contacts as $contact)
               <div>{{ $contact->lastname }}</div>
           @endforeach
       </x-card>
   @endsection
   ```

6. **Tailwind CSS**:
   - Use utility classes directly in Blade templates
   - Common patterns:
     - Containers: `container mx-auto px-4`
     - Grids: `grid grid-cols-1 md:grid-cols-2 gap-4`
     - Cards: `bg-white dark:bg-gray-800 rounded-lg shadow-md p-6`
     - Buttons: `bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded`
   - Dark mode: Use `dark:` prefix for dark mode variants
   - Responsive: Use `sm:`, `md:`, `lg:`, `xl:` prefixes
   - See `BLADE_TAILWIND_GUIDE.md` for detailed patterns

7. **Database Access** (Legacy Dolibarr Pattern):
   - Use the DoliDB class for database operations
   - Always use prepared statements or proper escaping for security
   - Example with escaping: `$sql = "SELECT field FROM ".MAIN_DB_PREFIX."table WHERE rowid = ".(int)$id;`
   - **Prefer Eloquent models for new code**

8. **Module Structure** (Legacy):
8. **Module Structure** (Legacy):
   - Each module follows a standard structure with core/, class/, lib/ subdirectories
   - Main entry point is typically named after the module
   - Use hooks and triggers for extensibility
   - **For new features, create Laravel controllers instead**

9. **Permissions**:
   - Check user permissions before operations using `$user->hasRight()`
   - Define permissions in module descriptor files
   - Use Laravel middleware for new routes when appropriate

10. **Language/Translation**:
    - All user-facing strings must be translatable
    - Use `$langs->trans()` for translations (legacy)
    - Use Laravel's `__()` helper for new translations
    - Add new strings to `en_US` language files only (Transifex handles other languages)

11. **Forms and CSRF Protection**:
    - Use `Form` class methods for generating form elements (legacy)
    - In Blade: Use `@csrf` directive for CSRF protection
    - Example:
    ```blade
    <form method="POST" action="{{ route('contact.store') }}">
        @csrf
        <!-- form fields -->
    </form>
    ```

### Git Workflow

1. **Branches**:
   - Main development happens in feature branches
   - Base new features on the main/develop branch
   - Use descriptive branch names: `feature/contact-refactoring`, `fix/csrf-validation`

2. **Commit Messages**:
   ```
   [KEYWORD] [ISSUENUM] Short description
   
   Longer description if needed
   ```
   - Keywords: FIX, NEW, CLOSE, ENHANCED, REFACTOR, etc.
   - Reference issue numbers when applicable
   - Be specific about what changed and why

3. **Pull Requests**:
   - Submit PRs with clear descriptions
   - Include screenshots for UI changes
   - Reference related issues
   - Follow the PR template in `.github/PULL_REQUEST_TEMPLATE.md`
   - Ensure CI passes before requesting review

### Common Commands

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=it_loads_the_contacts_list_page

# Code style check (if configured)
./vendor/bin/phpstan analyse

# Database operations
php artisan migrate
php artisan db:seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Asset compilation
npm run build          # Production build
npm run dev            # Development build with watch
```

### Things to Avoid

1. **Don't modify**:
   - `ChangeLog` file (auto-generated during release)
   - Language files except `en_US` (managed via Transifex)
   - Third-party libraries in `vendor/` (use Composer)
   - Compiled assets in `public/` (regenerated by Vite)
   - Files in `storage/` or `bootstrap/cache/` (generated files)

2. **Don't use**:
   - Direct global variable modifications without proper context
   - Deprecated PHP functions
   - Hard-coded strings for user-facing text
   - `require` or `include` statements (use `use` statements and autoloading)
   - Inline styles (use Tailwind CSS classes)
   - Raw SQL when Eloquent can be used

3. **Don't add**:
   - Credentials or sensitive data to the repository
   - Binary files without good reason
   - New dependencies without discussion
   - .php extensions to new route URLs
   - Direct database queries in views (use controllers)

4. **Don't mix**:
   - Business logic in views (keep in controllers/services)
   - HTML output in controllers (use Blade views)
   - Legacy and modern patterns in the same file (refactor completely)

### Testing

### Testing

- **Framework**: PHPUnit with Laravel testing utilities
- **Test Location**: `/tests` directory
  - `Feature/` - Integration/feature tests (test full requests)
  - `Unit/` - Unit tests (test individual classes/methods)
- Write tests for new controllers and critical business logic
- Run tests before committing: `php artisan test`
- Aim for meaningful test coverage, not just high percentages

### Testing Standards (Laravel/PHPUnit)

When writing Laravel tests (located in `/tests`), follow these conventions:

1. **Test Method Naming**:
   - All test methods must start with `it_` prefix
   - Method names should read as grammatically correct sentences
   - Example: `it_loads_the_contacts_list_page()`, `it_filters_contacts_by_lastname()`

2. **Test Attributes**:
   - All test methods must be annotated with `#[Test]` attribute
   - Import: `use PHPUnit\Framework\Attributes\Test;`

3. **Test Structure (Arrange, Act, Assert)**:
   - **Arrange**: Set up test data and preconditions
   - **Act**: Execute the code being tested
   - **Assert**: Verify the expected outcome
   - Use comments to clearly separate these sections

4. **Example Test**:
   ```php
   namespace Tests\Feature;
   
   use PHPUnit\Framework\Attributes\Test;
   use Tests\TestCase;
   use App\Models\Contact;
   
   class ContactTest extends TestCase
   {
       #[Test]
       public function it_filters_contacts_by_lastname(): void
       {
           // Arrange
           Contact::create(['lastname' => 'Smith', 'entity' => 1]);
           Contact::create(['lastname' => 'Johnson', 'entity' => 1]);
           
           // Act
           $response = $this->get(route('contact.list', ['search_lastname' => 'Smith']));
           
           // Assert
           $response->assertStatus(200);
           $response->assertSee('Smith');
           $response->assertDontSee('Johnson');
       }
   }
   ```

5. **Testing Best Practices**:
   - Use factories for test data creation when available
   - Use database transactions to keep tests isolated
   - Test both success and failure scenarios
   - Test validation rules
   - Test authorization/permissions
   - Use `$this->actingAs($user)` to test authenticated routes

### Common Patterns

1. **Modern Laravel Controller Pattern**:
   ```php
   <?php
   
   namespace App\Http\Controllers;
   
   use App\Models\Contact;
   use Illuminate\Http\Request;
   use Illuminate\View\View;
   use Illuminate\Http\RedirectResponse;
   
   class ListContacts extends Controller
   {
       public function __invoke(Request $request): View
       {
           // Get filter parameters
           $searchLastname = $request->input('search_lastname');
           
           // Query with Eloquent (parameter binding for security)
           $contacts = Contact::query()
               ->when($searchLastname, function($q) use ($searchLastname) {
                   return $q->where('lastname', 'like', '%' . $searchLastname . '%');
               })
               ->with('societe')
               ->paginate(25);
           
           return view('contact.list', compact('contacts'));
       }
   }
   ```

2. **Form Handling Pattern**:
   ```php
   public function store(Request $request): RedirectResponse
   {
       // Validate input
       $validated = $request->validate([
           'lastname' => 'required|string|max:255',
           'firstname' => 'nullable|string|max:255',
           'email' => 'required|email',
       ]);
       
       // Create record
       $contact = Contact::create($validated);
       
       // Redirect with success message
       return redirect()
           ->route('contact.show', $contact)
           ->with('success', 'Contact created successfully');
   }
   ```

3. **Blade View with Tailwind**:
   ```blade
   @extends('layouts.app')
   
   @section('title', 'Contact List')
   
   @section('content')
   <div class="container mx-auto px-4 py-8">
       <x-card title="Contacts">
           <div class="mb-4">
               <form method="GET" action="{{ route('contact.list') }}">
                   <input type="text" name="search_lastname" 
                          placeholder="Search by lastname"
                          class="border rounded px-4 py-2">
                   <x-button type="submit">Search</x-button>
               </form>
           </div>
           
           <table class="w-full">
               @foreach($contacts as $contact)
               <tr>
                   <td>{{ $contact->lastname }}</td>
                   <td>{{ $contact->email }}</td>
               </tr>
               @endforeach
           </table>
           
           {{ $contacts->links() }}
       </x-card>
   </div>
   @endsection
   ```

4. **Legacy Dolibarr Pattern** (for reference, avoid in new code):
   ```php
   // Old style - don't use in new code
   $sql = "SELECT t.rowid, t.lastname FROM ".MAIN_DB_PREFIX."socpeople as t";
   $sql .= " WHERE t.rowid = ".(int)$id;
   $resql = $db->query($sql);
   ```

## Module Development

### Modern Approach (Laravel)

When creating new features:

1. **Create a Controller**: `php artisan make:controller ContactController`
2. **Create a Model**: `php artisan make:model Contact -m` (with migration)
3. **Create Views**: In `resources/views/[module]/`
4. **Define Routes**: In `routes/web.php` with named routes
5. **Write Tests**: In `tests/Feature/`
6. **Run Migrations**: `php artisan migrate`

### Legacy Approach (Dolibarr)

For maintaining existing legacy modules in `app/Modules`:

1. Follow the standard Dolibarr structure with core/, class/, lib/ subdirectories
2. Use hooks and triggers for extensibility
3. Implement proper permissions and access controls
4. **Prefer refactoring to Laravel patterns when possible**

## API Development

### REST API (Laravel)

- Create API routes in `routes/api.php` (if needed)
- Use API Resources for response formatting
- Implement proper authentication (Sanctum, Passport)
- Follow RESTful conventions:
  - GET /api/contacts - List resources
  - GET /api/contacts/{id} - Show resource
  - POST /api/contacts - Create resource
  - PUT/PATCH /api/contacts/{id} - Update resource
  - DELETE /api/contacts/{id} - Delete resource
- Return appropriate HTTP status codes (200, 201, 404, 422, etc.)
- Document endpoints using OpenAPI/Swagger

### Legacy SOAP API

- Located in `app/Modules/api/`
- Maintained for backward compatibility
- Follow existing patterns for legacy endpoints

## Performance Considerations

- **Database**: Use indexes appropriately, avoid N+1 queries (use `with()` for eager loading)
- **Caching**: Use Laravel's cache for frequently accessed data
- **Queries**: Use Eloquent efficiently, paginate large datasets
- **Assets**: Compile and minify assets with Vite for production
- **Views**: Cache Blade templates automatically in production
- **Optimization**: Use `php artisan optimize` before deployment
- Test on both small and large datasets

## Laravel Controller Refactoring

When refactoring Dolibarr controllers to Laravel patterns:

1. **Delete Old Files**: After successful refactoring, delete the original Dolibarr PHP files that have been incorporated into Laravel controllers
2. **Move Code Into Controllers**: Logic should be in the controller method body, not executed from external files
3. **Use Eloquent Models**: Replace raw SQL queries with Eloquent ORM queries
4. **Use Helper Functions**: Wrap Dolibarr functions in `app/helpers.php` for compatibility
5. **Create Blade Views**: Separate presentation layer into Blade templates (can be minimal initially)
6. **Write Tests**: Follow the test standards (see Testing Standards section above) - **Note: For bulk refactoring, skip tests to speed up process**
7. **Document Changes**: Update `REFACTORING_PATTERN.md` with any new patterns discovered

### Speed Optimization for Bulk Refactoring

When refactoring multiple modules quickly:

1. **Skip CLI Testing**
   - Do not run `php artisan test` or other CLI commands during bulk refactoring
   - Manual verification only
   - Tests can be added later if needed
   - Focus on code completion, not validation

2. **Batch Operations**
   - Commit per module for reviewability
   - Use existing refactored modules as templates
   - Focus on functionality over perfection
   - Copy patterns from Contact module

3. **Simplified Approach**
   - Use `match` statements instead of if/elseif chains for actions
   - Create minimal views initially (enhance later)
   - Skip extensive error handling in first pass (add later)
   - Focus on core CRUD operations (create, read, update, delete)
   - Use simple redirects instead of complex responses

4. **Always Delete Old Files**
   - After refactoring is complete
   - Use `git rm app/Modules/{Module}/{file}.php`
   - Keep only class files and essential library files in Modules
   - Delete list.php, card.php, index.php, and similar action files

5. **Refactoring Template**
   ```php
   public function __invoke(Request $request): View|RedirectResponse
   {
       $action = GETPOST('action', 'alpha') ?: 'view';
       $id = GETPOSTINT('id');
       
       return match($action) {
           'create', 'add' => $this->create($request),
           'edit' => $this->edit($request, $id),
           'update' => $this->update($request, $id),
           'delete' => $this->delete($request, $id),
           default => $this->show($request, $id),
       };
   }
   ```

## Resources

### Project Documentation
- **REFACTORING_PATTERN.md** - How to refactor Dolibarr code to Laravel
- **BLADE_TAILWIND_GUIDE.md** - Blade templating and Tailwind CSS usage
- **LARAVEL_REFACTORING_PLAN.md** - Overall refactoring strategy and status
- **Contributing Guide**: `.github/CONTRIBUTING.md`

### External Resources
- **Laravel Documentation**: https://laravel.com/docs/11.x
- **Tailwind CSS Documentation**: https://tailwindcss.com/docs
- **Dolibarr Developer Documentation**: https://wiki.dolibarr.org/index.php?title=Developer_documentation
- **Dolibarr Forums**: https://www.dolibarr.org/forum.php
- **Issue Tracker**: https://github.com/Dolibarr/dolibarr/issues (upstream)

## Support

For questions:
- Check the project documentation files first (see Resources above)
- Check the Dolibarr Wiki
- Use the Dolibarr forums (not GitHub Issues)
- GitHub Issues are for bug reports and feature requests only

---

## Best Practices Summary

When working with this codebase:

### For New Features
1. ✅ Create Laravel controllers in `app/Http/Controllers`
2. ✅ Use Eloquent models for database access
3. ✅ Create Blade views in `resources/views`
4. ✅ Use Tailwind CSS for styling
5. ✅ Define named routes in `routes/web.php`
6. ✅ Write tests in `tests/Feature` or `tests/Unit`
7. ✅ Use Laravel's validation and security features

### For Refactoring Legacy Code
1. ✅ Follow patterns in `REFACTORING_PATTERN.md`
2. ✅ Move logic from files into controller methods
3. ✅ Replace raw SQL with Eloquent queries
4. ✅ Convert HTML output to Blade templates
5. ✅ Use `match` statements for action handling
6. ✅ Delete old files after successful refactoring
7. ✅ Update tests to reflect new structure

### Code Quality
1. ✅ Follow PSR standards for PHP code
2. ✅ Use type hints and return types
3. ✅ Write meaningful comments for complex logic
4. ✅ Keep methods small and focused (Single Responsibility)
5. ✅ Extract repeated code into reusable components/services
6. ✅ Validate all user input
7. ✅ Handle errors gracefully

### Security
1. ✅ Always use CSRF protection (`@csrf` in forms)
2. ✅ Validate and sanitize all input
3. ✅ Use Eloquent or prepared statements (never raw SQL with user input)
4. ✅ Check permissions before sensitive operations
5. ✅ Never commit credentials or secrets
6. ✅ Follow Laravel security best practices

### Collaboration
1. ✅ Write clear commit messages
2. ✅ Keep commits focused and atomic
3. ✅ Include tests with new features
4. ✅ Document complex logic
5. ✅ Update relevant documentation files
6. ✅ Request reviews for significant changes

---

When suggesting code changes, always:
1. Follow the existing code style in the file
2. Use Laravel patterns for new code, maintain consistency for legacy
3. Include necessary comments and documentation
4. Consider backwards compatibility with legacy code
5. Think about security implications
6. Respect the modular architecture
7. Prefer Eloquent over raw SQL
8. Use Blade for views, not echo/print statements
9. Test changes thoroughly before committing
