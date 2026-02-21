# GitHub Copilot Instructions for Dolibarr

This file provides instructions for GitHub Copilot to assist with development in the Dolibarr ERP & CRM repository.

## Project Overview

Dolibarr is an open-source ERP & CRM software designed for small, medium, and large companies, foundations, and freelancers. It provides comprehensive business management features including customer/supplier management, invoicing, inventory, projects, HR, and more.

- **License**: GPL-3.0-or-later
- **Primary Language**: PHP (with JavaScript enhancements)
- **Homepage**: https://www.dolibarr.org
- **Wiki**: https://wiki.dolibarr.org

## Technology Stack

- **Backend**: PHP 7.2+ minimum (current versions support PHP 8.x - check https://wiki.dolibarr.org/index.php/Releases for version support)
- **Databases**: MariaDB, MySQL, or PostgreSQL
- **Frontend**: JavaScript (vanilla JS and modern frameworks like Chart.js)
- **Template System**: Custom PHP templating
- **APIs**: REST and SOAP APIs available

## Project Structure

```
dolibarr/
├── htdocs/           # Main application directory (web root)
│   ├── admin/        # Administration modules
│   ├── core/         # Core libraries and functions
│   ├── includes/     # Third-party libraries (vendor directory)
│   ├── install/      # Installation wizard
│   ├── langs/        # Language files (i18n)
│   ├── theme/        # UI themes and styling
│   └── [modules]/    # Feature modules (product, invoice, user, etc.)
├── dev/              # Development tools and resources
├── doc/              # Documentation
├── scripts/          # Utility scripts
└── test/             # Test files
```

## Development Guidelines

### Code Style and Standards

1. **PHP Code Style**:
   - Follow PSR-1 and PSR-2 standards where possible
   - Use tabs for indentation (project convention)
   - Opening braces on same line for functions/methods
   - Clear variable and function naming

2. **File Headers**:
   - All PHP files must include copyright headers with GPL license information
   - Format: `/* Copyright (C) YEAR Author Name <email> */`

3. **Documentation**:
   - Use PHPDoc comments for classes, methods, and functions
   - Document parameters with `@param` and return values with `@return`

4. **Security**:
   - Always validate and sanitize user input
   - Use prepared statements for database queries
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

1. **Database Access**:
   - Use the DoliDB class for database operations
   - Always use prepared statements or proper escaping for security
   - Example with escaping: `$sql = "SELECT field FROM ".MAIN_DB_PREFIX."table WHERE rowid = ".(int)$id;`
   - Prefer parameterized queries when available

2. **Module Structure**:
   - Each module follows a standard structure with core/, class/, lib/ subdirectories
   - Main entry point is typically named after the module
   - Use hooks and triggers for extensibility

3. **Permissions**:
   - Check user permissions before operations using `$user->hasRight()`
   - Define permissions in module descriptor files

4. **Language/Translation**:
   - All user-facing strings must be translatable
   - Use `$langs->trans()` for translations
   - Add new strings to `en_US` language files only (Transifex handles other languages)

5. **Forms and CSRF Protection**:
   - Use `Form` class methods for generating form elements
   - Include CSRF tokens in forms: `newToken()`

### Git Workflow

1. **Branches**:
   - `develop`: Main development branch
   - Version branches (e.g., `19.0`, `20.0`): Stable release branches
   - Create feature branches from `develop`
   - Bug fixes: Target oldest affected version (N-2 if possible)

2. **Commit Messages**:
   ```
   [KEYWORD] [ISSUENUM] Short description
   
   Longer description if needed
   ```
   - Keywords: FIX, NEW, CLOSE, ENHANCED, etc.
   - Reference issue numbers when applicable

3. **Pull Requests**:
   - Submit PRs against `develop` for new features
   - Submit to version branches for bug fixes
   - Follow the PR template in `.github/PULL_REQUEST_TEMPLATE.md`

### Things to Avoid

1. **Don't modify**:
   - `ChangeLog` file (auto-generated during release)
   - Language files except `en_US` (managed via Transifex)
   - Third-party libraries in `htdocs/includes/` (use Composer)

2. **Don't use**:
   - Direct global variable modifications without proper context
   - Deprecated PHP functions
   - Hard-coded strings for user-facing text

3. **Don't add**:
   - Credentials or sensitive data to the repository
   - Binary files without good reason
   - New dependencies without discussion

### Testing

- Test infrastructure exists in `/test` directory
- Manual testing is primary validation method
- Test on multiple PHP versions if making core changes
- Test on different databases (MySQL/MariaDB and PostgreSQL)

### Common Patterns

1. **Loading a module page**:
   ```php
   <?php
   require '../main.inc.php';  // Load environment
   require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
   
   // Load translation files
   $langs->loadLangs(array("module@module"));
   
   // Access control
   restrictedArea($user, 'module', 0, '', 'read');
   
   // Parameters
   $action = GETPOST('action', 'aZ09');
   $id = GETPOST('id', 'int');
   ```

2. **Database operations**:
   ```php
   $sql = "SELECT t.rowid, t.field1, t.field2 FROM ".MAIN_DB_PREFIX."table as t";
   $sql .= " WHERE t.rowid = ".(int)$id;
   $resql = $db->query($sql);
   if ($resql) {
       $obj = $db->fetch_object($resql);
       // Process...
       $db->free($resql);
   }
   ```

3. **Creating forms with CSRF protection**:
   ```php
   print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
   print '<input type="hidden" name="token" value="'.newToken().'">';
   // Form fields...
   print '</form>';
   ```

## Module Development

When creating or modifying modules:

1. Use the Module Builder tool at `/modulebuilder/` for scaffolding
2. Follow the module descriptor pattern in `/core/modules/modModuleName.class.php`
3. Implement proper permissions and access controls
4. Add database tables with proper prefix handling
5. Include upgrade scripts for database schema changes

## API Development

- REST API: Located in `/api/`
- Follow existing API patterns for endpoints
- Document new endpoints
- Include proper authentication checks
- Return appropriate HTTP status codes

## Performance Considerations

- Use database indexes appropriately
- Cache frequently accessed data when possible
- Avoid N+1 query problems
- Optimize for both small and large datasets

## Testing Standards (Laravel/PHPUnit)

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
   use PHPUnit\Framework\Attributes\Test;
   
   #[Test]
   public function it_filters_contacts_by_lastname(): void
   {
       // Arrange
       Contact::create(['lastname' => 'Smith', 'entity' => 1]);
       Contact::create(['lastname' => 'Johnson', 'entity' => 1]);
       
       // Act
       $response = $this->get('/contact/list.php?search_lastname=Smith');
       
       // Assert
       $response->assertStatus(200);
       $response->assertSee('Smith');
       $response->assertDontSee('Johnson');
   }
   ```

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

- **Developer Documentation**: https://wiki.dolibarr.org/index.php?title=Developer_documentation
- **Contributing Guide**: `.github/CONTRIBUTING.md`
- **Forums**: https://www.dolibarr.org/forum.php
- **Issue Tracker**: https://github.com/Dolibarr/dolibarr/issues

## Support

For questions:
- Check the Wiki first
- Use the forums (not GitHub Issues)
- GitHub Issues are for bug reports and feature requests only

---

When suggesting code changes, always:
1. Follow the existing code style in the file
2. Include necessary comments and documentation
3. Consider backwards compatibility
4. Think about security implications
5. Respect the modular architecture
6. Test changes in a working Dolibarr installation when possible
