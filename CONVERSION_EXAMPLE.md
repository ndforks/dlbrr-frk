# Example: Converting a Legacy Module to Laravel

## Source File Analysis
**File**: `app/Modules/Societe/project.php`  
**Type**: Legacy procedural view file  
**Purpose**: Display list of projects for a third party (societe)

## Current Structure (Legacy)
```php
<?php
// Bootstrap Dolibarr
require '../main.inc.php';

// Load classes
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';

// Get parameters
$action = request()->input('action');
$socid = request()->integer('socid', 0);

// Security check
$result = restrictedArea($user, 'societe', $socid, '&societe');

// Business logic
$object = new Societe($db);
if ($socid > 0) {
    $object->fetch($socid);
}

// HTML output mixed with PHP
print '<html>';
print '<h1>Projects for ' . $object->name . '</h1>';
// ... more HTML ...
?>
```

## Target Structure (Laravel)

### 1. Controller: `app/Http/Controllers/Societe/ProjectSociete.php`
```php
<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectSociete extends Controller
{
    /**
     * Display projects for a third party
     */
    public function __invoke(Request $request, int $id): View|RedirectResponse
    {
        // Authorization check
        if (!auth()->user()->hasRight('societe', 'lire')) {
            abort(403);
        }

        // Load societe
        $societe = Societe::findOrFail($id);
        
        // Get projects
        $projects = Projet::where('fk_soc', $id)
            ->orderBy('ref', 'desc')
            ->paginate(25);

        return view('societe.projects', [
            'societe' => $societe,
            'projects' => $projects,
        ]);
    }
}
```

### 2. View: `resources/views/societe/projects.blade.php`
```blade
@extends('layouts.app')

@section('title', 'Projects - ' . $societe->nom)

@section('content')
<div class="container">
    <h1>Projects for {{ $societe->nom }}</h1>
    
    @if($projects->isEmpty())
        <p>No projects found.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    <td>{{ $project->ref }}</td>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->status }}</td>
                    <td>
                        <a href="{{ route('projet.show', $project->id) }}">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $projects->links() }}
    @endif
</div>
@endsection
```

### 3. Route: `routes/web.php`
```php
// Add to Societe route group
Route::prefix('societe')->name('societe.')->group(function () {
    // ... existing routes ...
    Route::get('/{id}/projects', ProjectSociete::class)->name('projects');
});
```

### 4. Test: `tests/Feature/Societe/ProjectSocieteTest.php`
```php
<?php

namespace Tests\Feature\Societe;

use Tests\TestCase;
use App\Models\User;
use App\Models\Societe;
use App\Models\Projet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectSocieteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_projects_for_a_societe(): void
    {
        // Arrange
        $user = User::factory()->create();
        $societe = Societe::factory()->create(['nom' => 'Test Company']);
        $project = Projet::factory()->create([
            'fk_soc' => $societe->id,
            'ref' => 'PROJ-001',
            'title' => 'Test Project'
        ]);

        // Act
        $response = $this->actingAs($user)
            ->get(route('societe.projects', $societe->id));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Test Company');
        $response->assertSee('PROJ-001');
        $response->assertSee('Test Project');
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        // Arrange
        $societe = Societe::factory()->create();

        // Act
        $response = $this->get(route('societe.projects', $societe->id));

        // Assert
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function it_requires_proper_permissions(): void
    {
        // Arrange
        $user = User::factory()->withoutPermission('societe', 'lire')->create();
        $societe = Societe::factory()->create();

        // Act
        $response = $this->actingAs($user)
            ->get(route('societe.projects', $societe->id));

        // Assert
        $response->assertStatus(403);
    }
}
```

### 5. Migration (if needed)
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Usually not needed - tables already exist
        // Add any missing indexes for performance
        Schema::table('llx_projet', function (Blueprint $table) {
            $table->index('fk_soc');
        });
    }

    public function down(): void
    {
        Schema::table('llx_projet', function (Blueprint $table) {
            $table->dropIndex(['fk_soc']);
        });
    }
};
```

## Conversion Checklist

For each legacy file to convert:

- [ ] **Analyze** - Understand what the file does
- [ ] **Controller** - Create controller with business logic
- [ ] **View** - Create Blade template with HTML
- [ ] **Route** - Add route with proper naming
- [ ] **Test** - Write comprehensive tests
- [ ] **Authorization** - Implement proper auth checks
- [ ] **Validation** - Add request validation if needed
- [ ] **Verify** - Test in browser
- [ ] **Update Links** - Update all links to use new route
- [ ] **Delete Legacy** - Remove old file after validation

## Benefits

### Before (Legacy)
- ❌ Mixed HTML and PHP in one file
- ❌ Global variables ($db, $user, $conf)
- ❌ No separation of concerns
- ❌ Hard to test
- ❌ No type safety
- ❌ Difficult to maintain

### After (Laravel)
- ✅ Clean separation: Controller → View
- ✅ Dependency injection
- ✅ Blade templating with inheritance
- ✅ Fully testable
- ✅ Type-safe with PHP 8.x
- ✅ Easy to maintain and extend
- ✅ Follows PSR standards

## Estimated Effort

**Per File:**
- Simple view file: 1-2 hours
- Medium complexity: 2-4 hours
- Complex with forms: 4-8 hours

**For Entire App:**
- 3,400 legacy files × 2 hours average = **6,800 hours**
- With 2 developers: **3,400 hours** ≈ **4-5 months** full-time
- With proper tooling/automation: **3-4 months**

## Automation Opportunities

1. **AST-based conversion tool** - Parse PHP and generate controllers
2. **Template extraction** - Auto-extract HTML to Blade
3. **Route generation** - Auto-generate route definitions
4. **Test scaffolding** - Auto-generate basic test structure

## Priority Order

1. **Most accessed pages** (user dashboard, societe list, product list)
2. **Forms** (create/edit pages with validation)
3. **Reports** (complex queries, exports)
4. **Admin pages** (configuration, settings)
5. **Rarely used features** (advanced tools)

This example demonstrates the complete pattern for converting legacy Dolibarr code to proper Laravel standards.
