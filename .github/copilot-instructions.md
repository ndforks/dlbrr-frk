# GitHub Copilot Instructions for Dolibarr

This file provides instructions for GitHub Copilot to assist with development in the Dolibarr ERP & CRM repository.

## Project Overview

Dolibarr is an open-source ERP & CRM software designed for small, medium, and large companies, foundations, and freelancers. It provides comprehensive business management features including customer/supplier management, invoicing, inventory, projects, HR, and more.

- **License**: GPL-3.0-or-later
- **Primary Language**: PHP (with JavaScript enhancements)
- **Homepage**: https://www.dolibarr.org
- **Wiki**: https://wiki.dolibarr.org

## Technology Stack

- **Backend**: PHP 7.2+ (check https://wiki.dolibarr.org/index.php/Releases for version support)
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
   - Format: `/* Copyright (C) YEAR Name <email> */`

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
   - Always use prepared statements or proper escaping
   - Example: `$db->query($sql)` with escaped variables

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
   $sql = "SELECT * FROM ".MAIN_DB_PREFIX."table WHERE rowid = ".(int)$id;
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
