# Laravel Standards Refactoring Plan

## Current State Analysis

### What Was Completed (Previous Work)
✅ **Phase 1: Helper Function Replacement** (~25,000 replacements)
- Replaced GETPOST/GETPOSTINT/GETPOSTISSET with Laravel request() methods
- Replaced accessforbidden() → abort(403)
- Replaced dol_print_error() → abort(500)
- Covered: 1,700+ files across app/Http/Controllers and app/Modules

✅ **Phase 2: Routes and Redirects** (Just Completed)
- Removed ALL .php extensions from routes
- Added named routes to all routes
- Converted all controller redirects to use route() helper
- Covered: routes/web.php + 45 controllers

### What Remains: The BIG Challenge

**app/Modules Directory: ~3,985 PHP files**
- 576 class files (.class.php) - Some have namespaces, many don't
- ~3,400 procedural/view files - Mix HTML and PHP (legacy Dolibarr pattern)
- These are NOT just files needing namespaces - they're entire legacy pages

## The Reality

The files in `app/Modules` are **legacy Dolibarr application files**, not library code:
- They mix HTML output with business logic
- They use global variables extensively  
- They rely on Dolibarr's initialization system (main.inc.php)
- They are essentially view+controller+logic all in one file

### Example: app/Modules/Societe/project.php
```php
// This is a full page file:
require '../main.inc.php';  // Bootstrap Dolibarr
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
// ... lots of HTML mixed with PHP
```

## Proper Laravel Refactoring Strategy

### Option A: Full Rewrite (Recommended, but massive)
Convert each legacy page file to:
1. **Controller** (in app/Http/Controllers) - Handle requests
2. **Blade View** (in resources/views) - Render HTML
3. **Route** (in routes/web.php) - Define URL
4. **Model** (already exists in app/Models) - Data layer
5. **Tests** (in tests/) - Validation

**Scope**: ~3,400 files → ~3,400 controllers + ~3,400 views + routes + tests
**Estimated Effort**: 6-12 months of development work

### Option B: Hybrid Approach (Pragmatic)
Keep legacy files but modernize incrementally:
1. ✅ Keep controllers already refactored (65 controllers in app/Http/Controllers)
2. ✅ Use routes/web.php for new Laravel routes
3. ⚠️ Leave app/Modules as compatibility layer for non-refactored pages
4. 🔄 Refactor modules one at a time as needed
5. 🔄 Add new features using pure Laravel (controllers + views)

**Current Status**: ~10% refactored (65/650 estimated needed modules)

### Option C: Gradual Migration (Most Realistic)
1. ✅ **Already Done**: Helper functions modernized
2. ✅ **Already Done**: Routes use Laravel conventions
3. 🔄 **Next**: Convert class files to use PSR-4 autoloading and namespaces
4. 🔄 **Then**: One module at a time, create proper controllers + views
5. 🔄 **Finally**: Remove legacy files after validation

## Immediate Next Steps (Achievable)

### Step 1: Fix PSR-4 Compliance for Existing Classes
Target: 576 .class.php files that already exist as classes

**What to do**:
- Ensure all classes have proper namespaces (many already do)
- Follow PSR-4 autoloading conventions
- Move classes to match namespace structure if needed
- Add proper use statements instead of require_once

**Example**:
```php
// Current: app/Modules/Societe/canvas/individual/actions_card_individual.class.php
namespace App\Modules\Societe\Canvas\Individual;

class ActionsCardIndividual extends ActionsCardCommon {
    // Already has namespace!
}

// Just needs: Better path or autoloading config
```

### Step 2: Create Documentation for Refactoring Pattern
Document the pattern for converting a legacy module to Laravel:

**Template**:
1. Identify the legacy page file (e.g., project.php)
2. Extract business logic → Controller method
3. Extract HTML → Blade view
4. Add route with proper naming
5. Write tests
6. Update links/redirects
7. Delete legacy file

### Step 3: Prioritize High-Value Modules
Focus on most frequently accessed modules first:
- Contact (✅ Already done)
- Societe/Third parties (✅ Controller done, but modules remain)
- Products (✅ Controller done)
- Invoices (✅ Controller done)
- Orders (✅ Controller done)
- etc.

## Recommendations

### For Immediate Progress
1. **Accept hybrid state**: Laravel controllers coexist with legacy Modules
2. **Focus on new code**: Write all new features in pure Laravel
3. **Refactor incrementally**: One module per sprint/week
4. **Prioritize by usage**: Refactor most-used pages first

### For Long-term Success
1. **Set realistic timeline**: 6-12 months for full modernization
2. **Create refactoring team**: Multiple developers working in parallel
3. **Maintain compatibility**: Keep legacy working during migration
4. **Test extensively**: Each refactored module needs full testing

### For PSR Standards Compliance
1. **Add namespace validation**: Use PHP-CS-Fixer or similar
2. **Configure autoloading**: Update composer.json for proper PSR-4
3. **Fix class paths**: Ensure file paths match namespaces
4. **Remove manual includes**: Replace require_once with autoloading

## Metrics

### Current State
- Total PHP files: 3,985
- Class files: 576 (14%)
- Procedural files: ~3,400 (86%)
- Refactored modules: ~65 (10% of needed modules)
- PSR-4 compliant: ~50% of class files

### Target State  
- Total PHP files: ~650 (controllers only)
- Class files: 100% (all code in classes)
- View files: ~3,400 Blade templates
- Refactored modules: 100%
- PSR-4 compliant: 100%

## Conclusion

The previous work successfully modernized helper functions and routes, but converting 3,400+ legacy procedural files to proper Laravel classes with views is a **major rewrite project**, not a refactoring task.

**Recommendation**: Adopt hybrid approach (Option B) and convert modules incrementally over time while maintaining compatibility with legacy code.
