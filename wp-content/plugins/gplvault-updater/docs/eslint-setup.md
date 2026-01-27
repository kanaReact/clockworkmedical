# ESLint Setup for GPLVault Update Manager

This document describes the ESLint configuration for maintaining WordPress coding standards and security best practices in JavaScript code.

## Overview

The GPLVault Update Manager uses ESLint with WordPress-specific rules and security plugins to ensure:
- Compliance with WordPress JavaScript coding standards
- Detection of common security vulnerabilities
- Consistent code formatting and quality

## Configuration Files

### package.json
Contains the ESLint dependencies and npm scripts:
- `npm run lint:js` - Run ESLint on all JavaScript files
- `npm run lint:js:fix` - Run ESLint and automatically fix issues where possible

### .eslintrc.js
The main ESLint configuration file that:
- Extends WordPress recommended rules via `@wordpress/eslint-plugin`
- Adds security-focused rules from `eslint-plugin-security` and `eslint-plugin-no-unsanitized`
- Defines WordPress-specific globals (wp, jQuery, gplvault_ajax, etc.)
- Sets the text domain for internationalization to 'gplvault'

### .eslintignore
Excludes files from linting:
- Minified/bundled JavaScript files
- Node modules and vendor directories
- Build output directories

## Key Rules

### WordPress Standards
- **@wordpress/i18n-text-domain**: Ensures all translatable strings use the 'gplvault' text domain
- **WordPress formatting**: Enforces WordPress JavaScript coding standards

### Security Rules
- **no-eval**: Prevents use of eval() which can execute arbitrary code
- **no-unsanitized/method & property**: Detects unsafe DOM manipulation (innerHTML, outerHTML)
- **security/detect-eval-with-expression**: Additional eval detection
- **security/detect-unsafe-regex**: Warns about potentially dangerous regular expressions

### Code Quality
- **no-console**: Warns about console usage (except error and warn)
- **no-alert**: Warns about alert() usage
- **no-debugger**: Prevents debugger statements

## Usage

### Running ESLint

```bash
# Check all JavaScript files
npm run lint:js

# Auto-fix issues where possible
npm run lint:js:fix

# Check a specific file
npx eslint path/to/file.js
```

### Common Issues and Solutions

1. **Line ending errors (CRLF vs LF)**
   - Configure your editor to use LF line endings
   - Use `.editorconfig` settings
   - Run `npm run lint:js:fix` to auto-fix

2. **Missing text domain**
   - Always include 'gplvault' as the second parameter in translation functions
   - Example: `__('Text', 'gplvault')`

3. **Unsafe DOM manipulation**
   - Use WordPress escaping functions or safe DOM methods
   - Avoid direct innerHTML assignments

## Integration with Development Workflow

- ESLint can be integrated with your editor for real-time feedback
- Consider adding pre-commit hooks to run ESLint automatically
- The configuration is excluded from the distribution build via `.distignore`

## Maintenance

When updating dependencies:
```bash
npm update
```

To check for outdated packages:
```bash
npm outdated
