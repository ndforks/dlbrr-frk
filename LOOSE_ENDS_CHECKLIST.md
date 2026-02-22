# Loose Ends Checklist - Routes and Controllers Audit

## Issues Identified

### 1. ❌ Redundant Route Pattern
**Problem**: Routes have both `/` (index) and `/list` routes in the same prefix, which is redundant.

**Current Pattern:**
```php
Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', ProductIndex::class)->name('index');        // Redundant
    Route::get('/{id?}', ShowProduct::class)->name('show');
    Route::get('/list', ListProduct::class)->name('list');      // Should be the index
});
```

**Should be:**
```php
Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', ListProduct::class)->name('list');          // List is the index
    Route::get('/{id}', ShowProduct::class)->name('show');
});
```

### 2. ✅ Missing Societe Sub-Routes and Controllers - COMPLETED

**Created and routed:**
- [x] `societe/projects` - ProjectsController created
- [x] `societe/notes` - NotesController created (with CRUD actions)
- [x] `societe/documents` - DocumentsController created
- [x] `societe/contacts` - ContactsController created
- [x] `societe/consumption` - ConsumptionController created
- [x] `societe/prices` - PricesController created
- [x] `societe/messaging` - MessagingController created
- [x] `societe/vcard` - VCardController created (VCard export)

**Legacy files deleted:**
- All legacy PHP files in `app/Modules/Societe/` have been removed

### 3. ✅ Missing Contact Sub-Routes and Controllers - COMPLETED

**Created and routed:**
- [x] `contact/projects` - ProjectsController created
- [x] `contact/notes` - NotesController created (with CRUD actions)
- [x] `contact/documents` - DocumentsController created
- [x] `contact/agenda` - AgendaController created
- [x] `contact/consumption` - ConsumptionController created
- [x] `contact/info` - InfoController created
- [x] `contact/perso` - PersoController created (with CRUD actions)
- [x] `contact/messaging` - MessagingController created
- [x] `contact/vcard` - VCardController created (VCard export)

**Legacy files deleted:**
- All legacy PHP files in `app/Modules/Contact/` have been removed

### 4. ⏳ Raw SQL Queries - Services Created (Controllers to be refactored)

**Services created (ready for controller refactoring):**
- [x] `BankService` - For ListBank, ShowBank
- [x] `BookmarkService` - For BookmarksIndex, ShowBookmarks
- [x] `CategoryService` - For CategoriesIndex, ShowCategories
- [x] `AccountancyService` - For AccountancyIndex, JournalAccountancy
- [x] `EcmService` - For EcmIndex, AutoIndexEcm
- [x] `StockService` - For ShowStock, MovementStock, StockIndex
- [x] `HrmService` - For PositionHrm, HrmIndex
- [x] `SupplierProposalService` - For ListSupplierProposal, SupplierProposalIndex
- [x] `BookcalService` - For CalendarBookcal, BookcalIndex
- [x] `ProductAttributeService` - For ListVariants (already done)

**Controllers still to refactor (now have services available):**
- [ ] `ListBank` - Refactor to use BankService
- [ ] `ShowBank` - Refactor to use BankService
- [ ] `BookmarksIndex` - Refactor to use BookmarkService
- [ ] `ShowBookmarks` - Refactor to use BookmarkService
- [ ] `CategoriesIndex` - Refactor to use CategoryService
- [ ] `ShowCategories` - Refactor to use CategoryService
- [ ] `AccountancyIndex` - Refactor to use AccountancyService
- [ ] `JournalAccountancy` - Refactor to use AccountancyService
- [ ] `EcmIndex` - Refactor to use EcmService
- [ ] `AutoIndexEcm` - Refactor to use EcmService
- [ ] `ShowStock` - Refactor to use StockService
- [ ] `MovementStock` - Refactor to use StockService
- [ ] `StockIndex` - Refactor to use StockService
- [ ] `PositionHrm` - Refactor to use HrmService
- [ ] `HrmIndex` - Refactor to use HrmService
- [ ] `ListSupplierProposal` - Refactor to use SupplierProposalService
- [ ] `SupplierProposalIndex` - Refactor to use SupplierProposalService
- [ ] `CalendarBookcal` - Refactor to use BookcalService
- [ ] `BookcalIndex` - Refactor to use BookcalService

**Still need services for:**
- [ ] Event Organization controllers
- [ ] Website controllers
- [ ] Supplier/Fourn controllers

### 5. ⏳ Tests - In Progress

**Service tests created:**
- [x] BankServiceTest (8 test methods)
- [x] BookmarkServiceTest (9 test methods)
- [x] CategoryServiceTest (10 test methods)

**Service tests still needed:**
- [ ] AccountancyServiceTest
- [ ] EcmServiceTest
- [ ] StockServiceTest
- [ ] HrmServiceTest
- [ ] SupplierProposalServiceTest
- [ ] BookcalServiceTest
- [ ] ProductAttributeServiceTest (service exists)

**Controller tests needed:**
- [ ] Societe sub-controllers (8 controllers)
- [ ] Contact sub-controllers (9 controllers)
- [ ] Refactored controllers using services

### 6. ❌ Controllers Not Using Traits Yet

**Show controllers not refactored (11 remaining):**
- [ ] ShowStock
- [ ] ShowConferenceOrBoothEventOrganization
- [ ] ShowUser (simple redirect)
- [ ] ShowFourn
- [ ] ShowVariants
- [ ] ShowCategories
- [ ] And 5 more...

**List controllers not refactored (20 remaining):**
- [ ] ListPropal
- [ ] ListProjet
- [ ] ListManufacturingOrders
- [ ] ListSupplierProposal
- [ ] ListVariants (REFACTORED but still needs HasSearchableList trait)
- [ ] And 15 more...

## Priority Actions Required

### HIGH PRIORITY
1. ✅ Fix redundant routes - Remove `/` index routes, make `/list` the default
2. ✅ Create missing Societe sub-route controllers (project, note, document, contact, etc.)
3. ✅ Create missing Contact sub-route controllers (project, note, document, etc.)
4. ⏳ Complete SQL to Eloquent conversion with services (started with ProductAttributeService)

### MEDIUM PRIORITY
5. ⏳ Apply HasCrudActions/HasSearchableList traits to remaining controllers
6. ⏳ Add PHPUnit tests for all services and refactored controllers
7. ⏳ Remove `global $db` usage from all controllers

### LOW PRIORITY
8. ⏳ Document all new services in SOLID_DRY_REFACTORING_SUMMARY.md
9. ⏳ Create migration guide for remaining legacy PHP files

## Progress Summary

**New Controllers Created:** 17 of 24
- ✅ 8 Societe sub-controllers
- ✅ 9 Contact sub-controllers
- ⏳ 0 of 7 miscellaneous controllers

**New Services Created:** 9 of ~12 core services
- ✅ BankService
- ✅ BookmarkService
- ✅ CategoryService
- ✅ AccountancyService
- ✅ EcmService
- ✅ StockService
- ✅ HrmService
- ✅ SupplierProposalService
- ✅ BookcalService
- ⏳ EventOrganizationService (pending)
- ⏳ WebsiteService (pending)
- ⏳ FournisseurService (pending)

**New Tests Created:** 27 of ~50-60 tests
- ✅ 8 BankService tests
- ✅ 9 BookmarkService tests
- ✅ 10 CategoryService tests
- ⏳ ~30 more service tests needed
- ⏳ ~20 controller tests needed

## Notes

- Many legacy `.php` files in `app/Modules/*/` are display pages that need Laravel controllers
- The pattern should be: `Module/SubAction` becomes `ModuleSubActionController`
- Example: `Societe/project.php` → `SocieteProjectController` or `ProjectsPerSocieteController`
- All raw SQL must go through service layer with Eloquent ORM
