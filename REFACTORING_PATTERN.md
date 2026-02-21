# Refactoring Pattern: From DolibarrController to Laravel Controllers

This document describes the pattern used to refactor Dolibarr PHP files into proper Laravel controllers.

## Problem

The original implementation used a `DolibarrController` base class that executed Dolibarr PHP files directly:

```php
class ListContacts extends DolibarrController
{
    public function __invoke(): Response
    {
        return $this->executeDolibarrFile('Contact/list.php');
    }
}
```

This approach had several issues:
- Code was not in the controller (just executed from a separate file)
- Used `require` statements instead of `use` statements
- Had hard-coded SQL queries instead of Eloquent
- Mixed business logic with presentation
- Difficult to test in isolation

## Solution

We refactored controllers to follow Laravel best practices:

### 1. Code Moved INTO Controllers

Instead of executing external files, the controller logic is now within the controller class itself:

```php
class ListContacts extends Controller
{
    public function __invoke(Request $request): View
    {
        // Logic is here, not in an external file
        $contacts = Contact::query()
            ->with('societe')
            ->get();
            
        return view('contact.list', ['contacts' => $contacts]);
    }
}
```

### 2. Replace `require` with `use` Statements

**Before:**
```php
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
```

**After:**
```php
use App\Models\Contact;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;
```

### 3. Replace Hard-Coded SQL with Eloquent

**Before:**
```php
$sql = "SELECT s.rowid as socid, s.nom as name, s.name_alias as alias,";
$sql .= " p.rowid, p.ref_ext, p.lastname as lastname, p.statut, p.firstname";
$sql .= " FROM ".MAIN_DB_PREFIX."socpeople as p";
$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."societe as s ON s.rowid = p.fk_soc";
$sql .= " WHERE p.entity IN (".getEntity('contact').")";
if ($search_lastname != '') {
    $sql .= natural_search("p.lastname", $search_lastname);
}
$resql = $db->query($sql);
```

**After:**
```php
$query = Contact::query()
    ->with('societe')  // Eager load relationships
    ->select('llx_socpeople.*');
    
if ($searchLastname) {
    $query->where('lastname', 'like', "%{$searchLastname}%");
}

$contacts = $query->get();
```

### 4. Replace Dolibarr Functions with Helpers

**Before:**
```php
$search_all = GETPOST('search_all', 'alphanohtml');
$search_id = GETPOSTINT('search_id');
```

**After:**
The helper functions are defined in `app/helpers.php` and work with Laravel's request system:

```php
function GETPOST($paramname, $check = 'alphanohtml', $method = 0) {
    $request = request();
    $value = $request->input($paramname);
    // Apply validation/filtering
    return $value;
}
```

This allows gradual migration while maintaining compatibility.

### 5. Use Views Instead of Direct Output

**Before:**
```php
print '<table>';
print '<tr><td>'.$obj->lastname.'</td></tr>';
print '</table>';
```

**After:**
```php
return view('contact.list', [
    'contacts' => $contacts,
    'total' => $total,
]);
```

## Example: ListContacts Refactoring

### Original Structure
```
app/
  Modules/
    Contact/
      list.php (1932 lines of mixed logic/presentation)
  Http/
    Controllers/
      Contact/
        ListContacts.php (just executes list.php)
```

### Refactored Structure
```
app/
  Models/
    Contact.php (Eloquent model)
    Societe.php (Eloquent model)
  Http/
    Controllers/
      Contact/
        ListContacts.php (contains logic, uses Eloquent)
  helpers.php (Dolibarr compatibility functions)
resources/
  views/
    contact/
      list.blade.php (presentation layer)
tests/
  Feature/
    ListContactsControllerTest.php (comprehensive tests)
```

## Key Components

### 1. Eloquent Models

```php
// app/Models/Contact.php
class Contact extends Model
{
    protected $table = 'llx_socpeople';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
```

### 2. Helper Functions

Located in `app/helpers.php`, these provide compatibility with Dolibarr functions:
- `GETPOST()` - Get request parameters with validation
- `GETPOSTINT()` - Get integer parameters
- `dol_print_date()` - Format dates
- `price()` - Format prices
- `img_picto()` - Display icons
- `newToken()` - CSRF token generation

### 3. Blade Views

```blade
@foreach($contacts as $contact)
    <tr>
        <td>{{ $contact->lastname }}</td>
        <td>{{ $contact->firstname }}</td>
        <td>
            @if($contact->societe)
                {{ $contact->societe->nom }}
            @endif
        </td>
    </tr>
@endforeach
```

### 4. Comprehensive Tests

```php
public function test_search_by_lastname_works(): void
{
    Contact::create(['lastname' => 'Smith']);
    Contact::create(['lastname' => 'Johnson']);
    
    $response = $this->get('/contact/list.php?search_lastname=Smith');
    
    $response->assertSee('Smith');
    $response->assertDontSee('Johnson');
}
```

## Benefits

1. **Testability**: Controllers can be unit tested without hitting the database
2. **Maintainability**: Clear separation of concerns
3. **Type Safety**: Type hints and IDE autocomplete
4. **Performance**: Eloquent's query builder is optimized
5. **Security**: Built-in protection against SQL injection
6. **Gradual Migration**: Helper functions allow incremental refactoring

## Migration Path

1. Create Eloquent models for the database tables
2. Create helper functions for commonly used Dolibarr functions
3. Refactor one controller at a time:
   - Move code into the controller
   - Replace SQL with Eloquent
   - Create a Blade view
   - Write tests
4. Test thoroughly
5. Deploy incrementally

## Testing

All refactored controllers should have comprehensive tests:

```bash
php artisan test --filter=ListContactsControllerTest
```

Example tests include:
- Basic page loading
- Search functionality
- Pagination
- Relationships
- Edge cases

## Future Improvements

1. Create more Eloquent models for other tables
2. Add more helper functions as needed
3. Create reusable view components
4. Add API endpoints using the same models
5. Implement proper authentication/authorization
6. Add caching where appropriate

## Conclusion

This refactoring pattern demonstrates how to modernize legacy Dolibarr code while maintaining functionality and enabling gradual migration. The key is to:
- Keep changes minimal and focused
- Maintain backward compatibility through helpers
- Write comprehensive tests
- Follow Laravel best practices
- Document the approach for future refactoring
