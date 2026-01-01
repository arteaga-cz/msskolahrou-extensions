# Plugin Modernization Session - December 31, 2024

## Session Summary
Comprehensive modernization of MŠ Škola Hrou WordPress plugin from 2019 codebase to support modern PHP (8.0-8.4), WordPress 6.x, Elementor 3.x, and ACF 6.x.

## Version Change
- **From**: 1.0.0
- **To**: 1.1.0

## Critical Changes Made

### 1. Plugin Header Updates (`msskolahrou-extensions.php`)
- Added `Requires at least: 6.0`
- Added `Requires PHP: 8.0`
- Updated Elementor required: 2.5.9 → 3.5.0
- Updated Elementor recommended: 2.6.8 → 3.20.0

### 2. Elementor API Migration (Breaking Changes)
**Hook Change** (`includes/elementor/base/module-base.php`):
```php
// OLD (deprecated in Elementor 3.5)
add_action('elementor/widgets/widgets_registered', ...)
// NEW
add_action('elementor/widgets/register', ...)
```

**Widget Registration** (`includes/elementor/base/module-base.php`):
```php
// OLD
$widget_manager->register_widget_type(new $class_name());
// NEW
$widget_manager->register(new $class_name());
```

**Color/Typography Schemes** (events.php, menus.php widgets):
```php
// OLD (deprecated)
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
'scheme' => [
    'type' => Scheme_Color::get_type(),
    'value' => Scheme_Color::COLOR_4,
]

// NEW
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
'global' => [
    'default' => Global_Colors::COLOR_ACCENT,
]
```

**Mapping Reference**:
- `COLOR_1` → `Global_Colors::COLOR_PRIMARY`
- `COLOR_4` → `Global_Colors::COLOR_ACCENT`
- `TYPOGRAPHY_1` → `Global_Typography::TYPOGRAPHY_PRIMARY`
- `TYPOGRAPHY_3` → `Global_Typography::TYPOGRAPHY_TEXT`
- `TYPOGRAPHY_4` → `Global_Typography::TYPOGRAPHY_ACCENT`

### 3. Security Fixes

**SQL Injection Fix** (`includes/helpers.php`):
```php
// OLD (vulnerable)
$sql = "SELECT post_name FROM {$wpdb->posts} WHERE post_type='acf-field' AND post_excerpt='{$field_name}'";

// NEW (secure)
$sql = $wpdb->prepare(
    "SELECT post_name FROM {$wpdb->posts} WHERE post_type = %s AND post_excerpt = %s",
    'acf-field',
    $field_name
);
```

**Input Sanitization** (`includes/content-types/daily_menus.php`):
- Added capability check: `current_user_can('edit_post', $post_id)`
- Added input sanitization: `sanitize_text_field(wp_unslash($_POST['...']))`
- Added proper isset() checks before array access

### 4. PHP 8 Strict Typing
Fixed all loose comparisons (`==`/`!=` → `===`/`!==`) in:
- `plugin.php:116`
- `includes/helpers.php:16`
- `includes/content-types/events.php:115`
- `includes/content-types/daily_menus.php:93,236,251`
- `includes/content-types/projects.php:87,133`
- `includes/content-types/notifications.php:96`
- `includes/elementor/modules/forms/fields/dynamic-select.php:45-60`
- `includes/elementor/modules/events/widgets/events.php:1125,1128,1159,1164`
- `includes/elementor/modules/menus/widgets/menus.php:807`

### 5. Composer Dependencies
**composer.json**:
```json
{
  "require": {
    "php": ">=8.0",
    "mpdf/mpdf": "^8.1"
  }
}
```

**Upgraded packages**:
- mpdf/mpdf: 7.1.9 → 8.2.7
- setasign/fpdi: 1.6.2 → 2.6.4
- psr/log: 1.1.0 → 3.0.2
- myclabs/deep-copy: 1.9.3 → 1.13.4

## Files Modified
1. `msskolahrou-extensions.php`
2. `plugin.php`
3. `composer.json`
4. `includes/elementor/base/module-base.php`
5. `includes/elementor/modules/events/widgets/events.php`
6. `includes/elementor/modules/menus/widgets/menus.php`
7. `includes/helpers.php`
8. `includes/content-types/daily_menus.php`
9. `includes/content-types/events.php`
10. `includes/content-types/projects.php`
11. `includes/content-types/notifications.php`
12. `includes/elementor/modules/forms/fields/dynamic-select.php`

## Testing Checklist
- [ ] Plugin activates without errors on PHP 8.4
- [ ] Elementor widgets register and render correctly
- [ ] Daily menu PDF generation works with mPDF 8.2
- [ ] ACF field retrieval works (msshext_get_acf_key function)
- [ ] All custom post types functional (events, projects, daily_menus, testimonials)
- [ ] No deprecation warnings in WordPress debug log

## Known Patterns for Future Reference
- Elementor 3.5+ requires `register()` instead of `register_widget_type()`
- Global Colors/Typography replace deprecated Scheme classes
- Always use `$wpdb->prepare()` for SQL queries with user input
- Use strict comparisons (`===`) for PHP 8 compatibility
