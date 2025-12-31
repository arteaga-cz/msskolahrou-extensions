# MS Škola Hrou Extensions - Project Overview

## Plugin Information
- **Name**: MS Škola Hrou Extensions
- **Version**: Defined in `MSSHEXT_VERSION` constant
- **Namespace**: `MSSHEXT`
- **Main file**: `msskolahrou-extensions.php` (bootstrap), `plugin.php` (core Plugin class)
- **Language**: PHP (WordPress plugin with Elementor integration)

## Purpose
Custom WordPress plugin extending functionality for MS Škola Hrou website. Provides:
- Custom Post Types (CPTs) for school-related content
- Elementor widgets and modules
- Form extensions for Elementor Pro
- Custom shortcodes and helper functions

## Architecture

### Entry Points
1. **msskolahrou-extensions.php** - Plugin bootstrap file
   - Defines constants (paths, URLs, version)
   - Checks Elementor compatibility
   - Loads plugin on `plugins_loaded` hook

2. **plugin.php** - Main `MSSHEXT\Plugin` class (Singleton pattern)
   - Properties: `$_instance`, `$modules_manager`, `$editor`, `$admin`
   - Manages includes, autoloading, frontend assets
   - Initializes Elementor integration via `on_elementor_init()`

### Constants
| Constant | Purpose |
|----------|---------|
| `MSSHEXT_VERSION` | Plugin version |
| `MSSHEXT__FILE__` | Main plugin file path |
| `MSSHEXT_PLUGIN_BASE` | Plugin basename |
| `MSSHEXT_NAME` | Plugin name |
| `MSSHEXT_PATH` | Plugin directory path |
| `MSSHEXT_INCLUDES_PATH` | Includes directory path |
| `MSSHEXT_MODULES_PATH` | Modules directory path |
| `MSSHEXT_ASSETS_PATH` | Assets directory path |
| `MSSHEXT_URL` | Plugin URL |
| `MSSHEXT_ASSETS_URL` | Assets URL |
| `MSSHEXT_INCLUDES_URL` | Includes URL |
| `MSSHEXT_MODULES_URL` | Modules URL |

## Directory Structure
```
msskolahrou-extensions/
├── msskolahrou-extensions.php    # Bootstrap
├── plugin.php                    # Main Plugin class
├── includes/
│   ├── admin.php                 # Admin class
│   ├── helpers.php               # Helper functions
│   ├── shortcodes.php            # Shortcode definitions
│   ├── content-types/            # Custom Post Types
│   │   ├── daily_menus.php
│   │   ├── employees.php
│   │   ├── events.php
│   │   ├── notifications.php
│   │   ├── pages.php
│   │   ├── projects.php
│   │   └── testimonials.php
│   └── elementor/
│       ├── modules-manager.php   # Modules_Manager class
│       ├── base/
│       │   └── module-base.php   # Abstract Module_Base
│       └── modules/
│           ├── events/           # Events module
│           ├── menus/            # Daily menus module
│           └── forms/            # Form extensions
├── assets/
│   ├── css/frontend.css
│   └── js/frontend.js
└── views/
    └── files/
        ├── daily_menu.html
        └── daily_menu.php
```

## Custom Post Types

### 1. Events (`event`)
- **File**: `includes/content-types/events.php`
- **Functions**:
  - `msshext_register_cpt_event()` - Registers CPT
  - `msshext_event_columns()` - Admin column headers
  - `msshext_event_column_content()` - Admin column content
  - `msshext_event_sortable_columns()` - Sortable columns
  - `msshext_event_columns_orderby()` - Column ordering

### 2. Employees (`employee`)
- **File**: `includes/content-types/employees.php`
- **Functions**:
  - `msshext_register_cpt_employee()` - Registers CPT
  - `msshext_register_tax_employee_cat()` - Employee category taxonomy

### 3. Projects (`project`)
- **File**: `includes/content-types/projects.php`
- **Functions**:
  - `msshext_register_cpt_project()` - Registers CPT
  - `msshext_project_options()` - Project options
  - `msshext_register_tax_project_type()` - Project type taxonomy
  - `msshext_yoast_seo_breadcrumb_append_link_projects()` - Yoast breadcrumbs
  - `msshext_yoast_seo_breadcrumb_append_link_event()` - Event breadcrumbs

### 4. Daily Menus (`daily_menu`)
- **File**: `includes/content-types/daily_menus.php`
- **Functions**:
  - `msshext_register_cpt_daily_menu()` - Registers CPT
  - `msshext_daily_menu_columns()` - Admin columns
  - `msshext_daily_menu_update_title()` - Auto title update
  - `msshext_daily_menu_options()` - Menu options
  - `msshext_redirect_daily_menu_single()` - Redirect single view
  - `msshext_daily_menu_rss_description()` - RSS feed
  - `msshext_daily_menu_download()` - Download handler
  - `msshext_get_daily_menus()` - Query menus
  - `msshext_get_daily_menus_by_week()` - Query by week
  - `msshext_get_week_start_end()` - Week date range

### 5. Notifications (`notification`)
- **File**: `includes/content-types/notifications.php`
- **Functions**:
  - `msshext_register_cpt_notification()` - Registers CPT
  - `msshext_notification_columns()` - Admin columns
  - `msshext_notification_sortable_columns()` - Sortable columns

### 6. Testimonials (`testimonial`)
- **File**: `includes/content-types/testimonials.php`
- **Functions**:
  - `msshext_register_cpt_testimonial()` - Registers CPT

### 7. Pages (Extensions)
- **File**: `includes/content-types/pages.php`
- **Functions**:
  - `msshext_page_options()` - Extended page options

## Elementor Integration

### Modules Manager
- **Class**: `MSSHEXT\Elementor\Modules_Manager`
- **File**: `includes/elementor/modules-manager.php`
- Loads and manages all Elementor modules

### Base Module
- **Class**: `MSSHEXT\Elementor\Base\Module_Base`
- **File**: `includes/elementor/base/module-base.php`
- Abstract class for all modules
- Methods: `get_widgets()`, `init_widgets()`

### Events Module
- **Namespace**: `MSSHEXT\Elementor\Modules\Events`
- **Files**:
  - `includes/elementor/modules/events/module.php`
  - `includes/elementor/modules/events/widgets/events.php`
- **Widget**: `Events` - Displays event listings with month grouping

### Menus Module
- **Namespace**: `MSSHEXT\Elementor\Modules\Menus`
- **Files**:
  - `includes/elementor/modules/menus/module.php`
  - `includes/elementor/modules/menus/widgets/menus.php`
- **Widget**: `Menus` - Displays daily menu listings

### Forms Module
- **Namespace**: `MSSHEXT\Elementor\Modules\Forms`
- **Files**:
  - `includes/elementor/modules/forms/module.php`
  - `includes/elementor/modules/forms/fields/dynamic-select.php`
  - `includes/elementor/modules/forms/actions/better-redirect.php`
- **Components**:
  - `Dynamic_Select` - Dynamic select field type with data sources
  - `Better_Redirect` - Enhanced redirect action with shortcode support

## Helper Functions
**File**: `includes/helpers.php`

| Function | Purpose |
|----------|---------|
| `msshext_check_event_date_and_id()` | Validate event date and ID |
| `msshext_get_relative_permalink()` | Get relative URL from permalink |
| `msshext_has_elementor()` | Check if Elementor is active |
| `msshext_get_acf_key()` | Get ACF field key by name |
| `msshext_get_scaled_image_path()` | Get scaled image path |
| `msshext_get_view()` | Load view template |
| `msshext_get_template_part()` | Get template part |
| `msshext_get_formatted_date()` | Format date string |

## Shortcodes
**File**: `includes/shortcodes.php`

| Shortcode | Function | Purpose |
|-----------|----------|---------|
| `[forced_excerpts]` | `msshext_shortcode_forced_excerpts()` | Force excerpts display |
| `[project_dates]` | `msshext_shortcode_project_dates()` | Display project dates |
| `[post_content]` | `msshext_shortcode_post_content()` | Display post content |
| `[event_date_start]` | `msshext_shortcode_event_date_start()` | Event start date |
| `[event_time_start]` | `msshext_shortcode_event_time_start()` | Event start time |

## Admin Class
- **Class**: `MSSHEXT\Admin`
- **File**: `includes/admin.php`
- **Methods**:
  - `enqueue_styles()` - Admin CSS
  - `enqueue_scripts()` - Admin JS
  - `plugin_action_links()` - Plugin action links
  - `plugin_row_meta()` - Plugin meta links
  - `register_element_categories()` - Elementor categories

## Assets
- **CSS**: `assets/css/frontend.css` - Frontend styles
- **JS**: `assets/js/frontend.js` - Frontend scripts

## Dependencies
- WordPress
- Elementor (required)
- Elementor Pro (for forms module)
- ACF (Advanced Custom Fields) - for custom field management

## ACF Configuration
- **Export file**: `acf-export.json` - Contains ACF field group configurations

## Development Tools
- **Grunt**: `Gruntfile.js` - Build automation
- **Composer**: `composer.json` - PHP dependencies
- **NPM**: `package.json` - Node dependencies
