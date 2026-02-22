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

### 2. ❌ Missing Societe Sub-Routes and Controllers

**Missing from routes/web.php:**
- [x] `societe/project` - Projects per third party
- [x] `societe/note` - Notes for third party
- [x] `societe/document` - Documents for third party
- [x] `societe/contact` - Contacts for third party
- [x] `societe/agenda` - Agenda/calendar for third party
- [x] `societe/consumption` - Consumption tracking
- [x] `societe/price` - Price management
- [x] `societe/paymentmodes` - Payment modes
- [x] `societe/website` - Website links
- [x] `societe/messaging` - Messaging
- [x] `societe/vcard` - VCard export
- [x] `societe/societecontact` - Society contact management

**Legacy files exist in:**
`app/Modules/Societe/*.php`

### 3. ❌ Missing Contact Sub-Routes and Controllers

**Missing from routes/web.php:**
- [x] `contact/project` - Projects per contact
- [x] `contact/note` - Notes for contact
- [x] `contact/document` - Documents for contact
- [x] `contact/agenda` - Agenda/calendar for contact
- [x] `contact/consumption` - Consumption tracking
- [x] `contact/info` - Contact info
- [x] `contact/perso` - Personal info
- [x] `contact/messaging` - Messaging
- [x] `contact/vcard` - VCard export
- [x] `contact/ldap` - LDAP integration

**Legacy files exist in:**
`app/Modules/Contact/*.php`

### 4. ❌ Raw SQL Queries Not Yet Converted

**Controllers still using raw SQL (from previous analysis):**
- [ ] `ListVariants` - **IN PROGRESS** (Service created, controller refactored)
- [ ] `ListSupplierProposal` - Needs SupplierProposalService
- [ ] `ShowCommande` (Fourn) - Using `global $db`
- [ ] `ShowFacture` (Fourn) - Using `global $db`
- [ ] `ListBank` - Needs BankService
- [ ] `ShowBank` - Using `global $db`
- [ ] `BookmarksIndex` - Using `global $db`
- [ ] `ShowBookmarks` - Using `global $db`
- [ ] `CategoriesIndex` - Using `global $db`
- [ ] `ShowCategories` - Using `global $db`
- [ ] `AccountancyIndex` - Using raw SQL
- [ ] `JournalAccountancy` - Using raw SQL
- [ ] `EcmIndex` - Using `global $db`
- [ ] `AutoIndexEcm` - Using `global $db`
- [ ] `ShowStock` - Using `global $db`
- [ ] `MovementStock` - Using `global $db`
- [ ] `StockIndex` - Using `global $db`
- [ ] `ShowConferenceOrBoothEventOrganization` - Using `global $db`
- [ ] `PositionHrm` - Using `global $db`
- [ ] `HrmIndex` - Using `global $db`
- [ ] `WebsiteIndex` - Using `global $db`
- [ ] `PageWebsite` - Using `global $db`
- [ ] `ShowFourn` - Using `global $db`
- [ ] `CommandeIndex` (Fourn) - Using `global $db`
- [ ] `FactureIndex` (Fourn) - Using `global $db`
- [ ] `FournIndex` - Using `global $db`
- [ ] `ShowVariants` - Using `global $db`
- [ ] `SupplierProposalIndex` - Using `global $db`
- [ ] `CalendarBookcal` - Using `global $db`
- [ ] `BookcalIndex` - Using `global $db`

### 5. ❌ Missing Tests

**Tests needed for:**
- [ ] ProductAttributeService (created but no test yet)
- [ ] ListVariants controller (refactored but no test yet)
- [ ] All other services to be created
- [ ] All refactored controllers

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

## Estimated Controllers/Services Needed

**New Controllers Required:** ~24 (12 for Societe, 10 for Contact, 2 misc)
**New Services Required:** ~25-30 (for SQL conversion)
**New Tests Required:** ~50-60 (service + controller tests)

## Notes

- Many legacy `.php` files in `app/Modules/*/` are display pages that need Laravel controllers
- The pattern should be: `Module/SubAction` becomes `ModuleSubActionController`
- Example: `Societe/project.php` → `SocieteProjectController` or `ProjectsPerSocieteController`
- All raw SQL must go through service layer with Eloquent ORM
