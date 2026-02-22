# View Refactoring Status

## Current Progress: 27 of 186 files (14.5%)

### Completed Files (27)

#### Fully Refactored with Clean Tailwind CSS (16 files)
These views use modern Blade templating with Tailwind CSS and minimal legacy code:

1. **Contact Module (1)**
   - contact/list.blade.php

2. **Admin Module (3)**
   - admin/system.blade.php  
   - admin/index.blade.php
   - admin/modules.blade.php

3. **Event Organization (1)**
   - eventorganization/index.blade.php

4. **Accounting (1)**
   - accountancy/journal/index.blade.php

5. **Website Module (4)**
   - website/page.blade.php
   - website/page-edit-content.blade.php
   - website/page-edit-source.blade.php
   - website/page-edit-meta.blade.php

6. **HRM Module (2)**
   - hrm/position_create.blade.php
   - hrm/position_edit.blade.php

7. **Supplier Module (6)**
   - fourn/index.blade.php
   - fourn/card.blade.php
   - fourn/commande/card.blade.php
   - fourn/commande/index.blade.php
   - fourn/facture/card.blade.php
   - fourn/facture/index.blade.php

#### Wrapped with Blade Layout (11 files)
These views now use layouts/app.blade.php but preserve legacy logic:

8. **Bank Module (4)**
   - bank/list.blade.php
   - bank/create.blade.php
   - bank/edit.blade.php
   - bank/show.blade.php

9. **Stock Module (5)**
   - stock/index.blade.php
   - stock/create.blade.php
   - stock/edit.blade.php
   - stock/show.blade.php
   - stock/movements.blade.php

### Remaining Files (159)

#### Blade Files with Legacy Code (6 files)
- eventorganization/conferenceorbooth/create.blade.php
- eventorganization/conferenceorbooth/edit.blade.php
- eventorganization/conferenceorbooth/show.blade.php
- hrm/index.blade.php
- hrm/position_card.blade.php
- accountancy/index.blade.php

#### Template Files (.tpl.php) (153 files)
Organized by directory:
- accountancy/tpl/
- adherents/tpl/
- asset/tpl/
- bom/tpl/
- comm/tpl/
- commande/tpl/
- compta/tpl/
- contact/tpl/
- contrat/tpl/
- core/tpl/
- delivery/tpl/
- don/tpl/
- ecm/tpl/
- eventorganization/tpl/
- expedition/tpl/
- expensereport/tpl/
- fichinter/tpl/
- fourn/commande/tpl/
- fourn/facture/tpl/
- hrm/tpl/
- modulebuilder/tpl/
- mrp/tpl/
- product/tpl/
- projet/tasks/tpl/
- projet/tpl/
- public/tpl/
- reception/tpl/
- societe/tpl/
- supplier_proposal/tpl/
- theme/tpl/
- ticket/tpl/
- variants/tpl/

## Refactoring Strategy

### Phase 1: Blade Files (CURRENT)
Convert all .blade.php files to use:
- `@extends('layouts.app')`
- Tailwind CSS classes
- Blade components (x-card, x-button, x-table)
- @php blocks for legacy logic

### Phase 2: Template Files  
Convert .tpl.php files to .blade.php:
- Understand context and usage
- Convert PHP syntax to Blade
- Apply Tailwind CSS
- Create components as needed

### Phase 3: Extract Business Logic
Separate concerns for complex views:
- Move database queries to controllers
- Extract complex logic to services
- Clean up views to pure presentation

## Next Steps

1. Complete remaining 6 blade files with legacy code
2. Start systematic conversion of .tpl.php files
3. Create additional Blade components as patterns emerge
4. Test all refactored views

## Notes

- All refactored files maintain backward compatibility
- Legacy Dolibarr functions preserved where needed
- Dark mode support added throughout
- Responsive design with mobile-first approach
