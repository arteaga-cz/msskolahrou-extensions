# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

WordPress plugin extending functionality for MS Škola Hrou (kindergarten website). Provides Custom Post Types, Elementor widgets, form extensions, and shortcodes.

**Dependencies**: WordPress, Elementor, Elementor Pro, ACF (Advanced Custom Fields)

## Commands

```bash
# Install dependencies
composer install
npm install

# Build CSS from SCSS (outputs to assets/css/)
npm run build:css

# Watch SCSS for changes during development
npm run watch:css

# Build distribution ZIP
npm run build:zip

# Full build (CSS + ZIP)
npm run build

# PHP syntax check (run from plugin directory)
cmd.exe /c "php -l includes/*.php"
```

## Source Structure

```
src/scss/           # SCSS source files
  ├── frontend.scss # Frontend widget styles
  ├── timeline.scss # Visual Timeline widget styles
  └── admin.scss    # Admin styles

assets/css/         # Compiled CSS output (generated)
```

## Commit Messages

- Follow Conventional Commits for the subject line (e.g., `feat:`, `fix:`, `chore:`) and keep it under 100 characters.
- Insert a blank line after the subject, then add a detailed body that explains the motivation, scope, and outcomes using short paragraphs and bullet lists indented with two spaces..
- Use sub-bullets to call out implementation details, tests, and documentation updates so reviewers can scan impact quickly.

Example:

```
feat: tighten profile domain validation

  Clarifies the service-level guardrails for profile slugs, prevents duplicate work across hooks,
  and documents the new RPC helper consumers should use.

  - Service updates
      - Ensure normalizeProfileSlug runs before every RPC call
      - Emit debug logs for rejected slugs to aid support escalations
  - Client behavior
      - Synchronize useProfileMutations to display inline error hints
  - Tests & docs
      - Add Vitest coverage for slug normalization edge cases
      - Extend docs/PROFILES.md with validation rules and troubleshooting steps
```

## Architecture

### Namespace & Entry Points
- **Namespace**: `MSSHEXT`
- **Bootstrap**: `msskolahrou-extensions.php` - Defines constants, checks Elementor compatibility
- **Core**: `plugin.php` - Singleton `MSSHEXT\Plugin` class with PSR-4-like autoloading

### Autoloading Convention
Classes in `MSSHEXT\` namespace are autoloaded from `includes/` directory:
- `MSSHEXT\Elementor\Modules_Manager` → `includes/elementor/modules-manager.php`
- `MSSHEXT\Elementor\Modules\Events\Module` → `includes/elementor/modules/events/module.php`

### Custom Post Types (`includes/content-types/`)
| File | CPT | Key Functions |
|------|-----|---------------|
| `events.php` | `event` | `msshext_register_cpt_event()` |
| `projects.php` | `project` | `msshext_register_cpt_project()`, `msshext_register_tax_project_type()` |
| `daily_menus.php` | `daily_menu` | `msshext_register_cpt_daily_menu()`, `msshext_get_daily_menus_by_week()` |
| `testimonials.php` | `testimonial` | `msshext_register_cpt_testimonial()` |

### Elementor Integration (`includes/elementor/`)
- **Base**: `base/module-base.php` - Abstract `Module_Base` class all modules extend
- **Manager**: `modules-manager.php` - `Modules_Manager` loads all modules

**Modules**:
| Module | Widget | Purpose |
|--------|--------|---------|
| `modules/events/` | `Events` | Event listings with month grouping |
| `modules/menus/` | `Menus` | Daily menu display |
| `modules/forms/` | — | `Dynamic_Select` field type + `Better_Redirect` action |

### Key Helper Functions (`includes/helpers.php`)
- `msshext_get_view($name)` - Load view template from `views/`
- `msshext_get_acf_key($field_name)` - Get ACF field key by name
- `msshext_get_formatted_date($date, $format)` - Format date strings
- `msshext_has_elementor()` - Check Elementor active

### Shortcodes (`includes/shortcodes.php`)
`[forced_excerpts]`, `[project_dates]`, `[post_content]`, `[event_date_start]`, `[event_time_start]`

## Constants
```php
MSSHEXT_PATH          // Plugin directory path
MSSHEXT_INCLUDES_PATH // includes/ directory
MSSHEXT_ASSETS_URL    // assets/ URL
MSSHEXT_VERSION       // Plugin version
```

## ACF Fields
Field configurations exported to `acf-export.json`. Color scheme field `msshext_color_scheme` used on posts/terms for body class styling.
