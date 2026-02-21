# Systematic Refactoring Plan for 30+ Dolibarr Modules

## Executive Summary

This document outlines the systematic approach to refactor all 30+ Dolibarr modules from the legacy `DolibarrController::executeDolibarrFile()` pattern to proper Laravel controllers with Eloquent ORM.

## Completed: Contact Module ✅

The Contact module serves as the reference implementation:
- ✅ **ListContacts**: Full list view with search, pagination, and Eloquent queries
- ✅ **ShowContact**: View/edit/create/update/delete functionality
- ✅ **ContactIndex**: Simple redirect to list
- ✅ **Models**: Contact model with Societe relationship
- ✅ **Tests**: 7 comprehensive tests (skipped for speed as requested)
- ✅ **Views**: Blade templates for list, show, edit, create
- ✅ **Old files deleted**: list.php and card.php removed

## Refactoring Approach

### Per Module Checklist

For each module, follow this pattern:

1. **Identify Core Files** (5 min)
   - list.php → ListController
   - card.php → ShowController  
   - index.php → IndexController
   - Any additional action files

2. **Create Eloquent Model** (10 min)
   - Define table name (llx_tablename)
   - Set primary key (rowid)
   - Disable timestamps
   - Define relationships

3. **Refactor Controllers** (20 min per controller)
   - Move code into controller methods
   - Replace SQL with Eloquent
   - Use helper functions (GETPOST, etc.)
   - Handle CRUD operations with match statements
   - No need for extensive views initially

4. **Delete Old Files** (2 min)
   - `git rm app/Modules/{Module}/*.php` (keep only class files)

5. **Commit** (1 min)
   - Commit per module for easy review

**Total per module: ~40 minutes**

## Module Priority List

### Phase 1: Core Business Modules (High Priority)

1. **Societe** (Companies) - 3 controllers
   - Models: Societe (already exists)
   - Files: list.php, card.php, index.php

2. **Product** - 3 controllers + Stock submodule
   - Models: Product (already exists)
   - Files: list.php, card.php, index.php
   - Submodule: Stock (movement.php, list.php, card.php)

3. **Commande** (Orders) - 3 controllers
   - Models: Commande (already exists)
   - Files: list.php, card.php, index.php

4. **Compta/Facture** (Invoices) - 3 controllers
   - Models: Facture
   - Files: list.php, card.php, index.php

5. **Projet** (Projects) - 3 controllers
   - Models: Projet
   - Files: list.php, card.php, index.php

### Phase 2: Secondary Modules (Medium Priority)

6. **Comm/Propal** (Proposals) - 3 controllers
7. **Ticket** (Support) - 3 controllers
8. **Fourn** (Suppliers) - 3 controllers + 2 submodules
9. **Expedition** (Shipments) - 3 controllers
10. **Contrat** (Contracts) - 3 controllers
11. **Fichinter** (Interventions) - 3 controllers
12. **Adherents** (Members) - 3 controllers
13. **Don** (Donations) - 3 controllers
14. **ExpenseReport** - 3 controllers
15. **Holiday** - 3 controllers

### Phase 3: Specialized Modules (Lower Priority)

16. **Hrm** (Human Resources) - 2 controllers
17. **Asset** - 3 controllers
18. **Bom** (Bill of Materials) - 3 controllers
19. **Mrp** (Manufacturing) - 3 controllers
20. **Loan** - 3 controllers
21. **SupplierProposal** - 3 controllers
22. **Variants** - 3 controllers
23. **Categories** - 2 controllers
24. **Bookmarks** - 2 controllers
25. **Accountancy** - 2 controllers
26. **Ecm** (Document Management) - 2 controllers
27. **EventOrganization** - 2 controllers
28. **Bookcal** (Booking) - 2 controllers
29. **Website** - 2 controllers
30. **User** - 3 controllers
31. **Admin** - 3 controllers

## Automation Strategy

### Quick Refactoring Template

For speed, use this simplified pattern for each controller:

```php
<?php

namespace App\Http\Controllers\{Module};

use App\Http\Controllers\Controller;
use App\Models\{Model};
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class {Action}{Module} extends Controller
{
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
    
    // Implement methods as needed
}
```

### Batch Operations

1. **Create all models first** (Day 1)
   - Generate models for all tables
   - Define relationships
   - Commit once

2. **Refactor List controllers** (Day 2-3)
   - All list.php files
   - Use Contact/ListContacts as template
   - Commit per module

3. **Refactor Card controllers** (Day 4-5)
   - All card.php files
   - Use Contact/ShowContact as template
   - Commit per module

4. **Cleanup** (Day 6)
   - Delete all old files
   - Update routes if needed
   - Final commit

## Model Generation

### Standard Model Template

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class {ModelName} extends Model
{
    protected $table = 'llx_{tablename}';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    // Define relationships as needed
}
```

### Common Relationships

- `belongsTo(Societe::class, 'fk_soc', 'rowid')` - Many records belong to one company
- `belongsTo(User::class, 'fk_user_creat', 'rowid')` - Created by user
- `hasMany(...)` - One-to-many relationships

## Testing Strategy

**As requested: Skip automated testing for speed**

- Manual verification of each module after refactoring
- Use browser testing for critical paths
- Rely on type hints and IDE validation
- Final smoke test of all modules

## Documentation Updates

### Guidelines Updates Required

Add to `.junie/guidelines.md` and `.github/copilot-instructions.md`:

1. **No CLI Testing During Refactoring**
   - Skip `php artisan test` to speed up process
   - Manual verification only
   - Tests can be added later if needed

2. **Batch Refactoring Rules**
   - Always delete old files after refactoring
   - Commit per module for reviewability
   - Use Contact module as reference template
   - Focus on functionality over perfection

3. **Speed Optimization**
   - Use match statements instead of if/elseif chains
   - Minimal views initially (can enhance later)
   - Skip extensive error handling (add later)
   - Focus on core CRUD operations

## Estimated Timeline

- **Phase 1** (5 modules): 3-4 hours
- **Phase 2** (10 modules): 6-8 hours  
- **Phase 3** (16 modules): 10-12 hours
- **Total**: ~20-24 hours of focused work

## Success Criteria

For each module:
- ✅ All controllers refactored (no more DolibarrController)
- ✅ Old PHP files deleted
- ✅ Eloquent models created
- ✅ Basic CRUD operations work
- ✅ Controllers use helper functions
- ✅ Code committed and pushed

## Risk Mitigation

1. **Complex Files**: For files >2000 lines, simplify to core actions only
2. **Dependencies**: Create dependent models first (e.g., Societe before Contact)
3. **Breaking Changes**: Commit frequently to allow rollback
4. **Testing**: Quick manual check per module, not comprehensive

## Next Actions

1. ✅ Complete Contact module
2. ✅ Create composer.json
3. ✅ Update guidelines
4. **Start Phase 1**: Societe module
5. **Continue systematically** through all modules

---

**Note**: This plan prioritizes speed and functionality over perfection. Enhancements (better views, comprehensive tests, error handling) can be added in subsequent iterations.
