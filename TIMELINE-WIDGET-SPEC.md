# Visual Timeline Widget - Specification

> Generated from brainstorming session. Reviewed by expert panel. Verified against Elementor documentation.

---

## Widget Identity

| Property | Value |
|----------|-------|
| **Name** | Visual Timeline |
| **Slug** | `msshext-visual-timeline` |
| **Category** | `msshext` (MSSHEXT widgets) |
| **Module Path** | `includes/elementor/modules/timeline/` |
| **CSS Path** | `assets/css/timeline.css` |
| **JS Path** | `assets/js/timeline.js` |
| **Min Elementor** | 3.5+ |

---

## Layout Modes

| Mode | Description |
|------|-------------|
| **Alternate** | Timeline centered, items alternate left/right. User chooses starting side (left or right). |
| **Left-aligned** | Timeline on left, all items on right. |

**Responsive Behavior**: Uses Elementor's built-in breakpoint system via `add_responsive_control()`. On mobile breakpoint (as defined in Elementor settings), both modes collapse to single column with timeline on left.

---

## Timeline Item Fields (Repeater)

| Field | Type | Required | Elementor Control | Notes |
|-------|------|----------|-------------------|-------|
| **Image** | Media | Yes | `Controls_Manager::MEDIA` | User-configurable aspect ratio via `Group_Control_Image_Size` |
| **Title** | Text | Yes | `Controls_Manager::TEXT` | Plain text, `label_block => true` |
| **Description** | WYSIWYG | No | `Controls_Manager::WYSIWYG` | Full rich text editor |
| **Icon** | Icons + Media | No | `Controls_Manager::ICONS` | Elementor icons OR custom SVG. If empty, icon hidden (line continues). Use `fa4compatibility` for migration. |
| **Label** | Text | No | `Controls_Manager::TEXT` | Appears on timeline opposite to card (e.g., "Dopoledne") |

### Repeater Implementation Pattern

```php
$repeater = new \Elementor\Repeater();

$repeater->add_control('image', [
    'label' => esc_html__('Image', 'msshext'),
    'type' => \Elementor\Controls_Manager::MEDIA,
    'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
]);

$repeater->add_control('title', [
    'label' => esc_html__('Title', 'msshext'),
    'type' => \Elementor\Controls_Manager::TEXT,
    'default' => esc_html__('Timeline Item', 'msshext'),
    'label_block' => true,
]);

$repeater->add_control('description', [
    'label' => esc_html__('Description', 'msshext'),
    'type' => \Elementor\Controls_Manager::WYSIWYG,
    'default' => '',
]);

$repeater->add_control('icon', [
    'label' => esc_html__('Icon', 'msshext'),
    'type' => \Elementor\Controls_Manager::ICONS,
    'fa4compatibility' => 'timeline_icon',
]);

$repeater->add_control('label', [
    'label' => esc_html__('Label', 'msshext'),
    'type' => \Elementor\Controls_Manager::TEXT,
    'placeholder' => esc_html__('e.g., Morning', 'msshext'),
]);

$this->add_control('timeline_items', [
    'label' => esc_html__('Timeline Items', 'msshext'),
    'type' => \Elementor\Controls_Manager::REPEATER,
    'fields' => $repeater->get_controls(),
    'default' => [...],
    'title_field' => '{{{ title }}}',
]);
```

---

## Style Controls

### Global Color Integration

Use Elementor's Global Colors system instead of hardcoded defaults:

```php
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

// Example: Line color with global default
$this->add_control('line_color', [
    'label' => esc_html__('Line Color', 'msshext'),
    'type' => \Elementor\Controls_Manager::COLOR,
    'global' => [
        'default' => Global_Colors::COLOR_PRIMARY,
    ],
    'selectors' => [
        '{{WRAPPER}} .msshext-timeline-line' => 'background-color: {{VALUE}};',
    ],
]);
```

### Available Global Colors

| Constant | Purpose | Suggested Usage |
|----------|---------|-----------------|
| `Global_Colors::COLOR_PRIMARY` | Primary brand color | Timeline line, icon background |
| `Global_Colors::COLOR_SECONDARY` | Secondary color | Progress line color |
| `Global_Colors::COLOR_TEXT` | Text color | Label text |
| `Global_Colors::COLOR_ACCENT` | Accent color | Alternative icon states |

### Timeline Line

| Control | Type | Global Default |
|---------|------|----------------|
| Color (default) | `Controls_Manager::COLOR` | `Global_Colors::COLOR_PRIMARY` |
| Color (progress/scrolled) | `Controls_Manager::COLOR` | `Global_Colors::COLOR_SECONDARY` |
| Width | `Controls_Manager::SLIDER` (px) | `3px` |
| Style | `Controls_Manager::SELECT` | `solid` |

> **Note**: Style options are CSS `border-style` values: `solid`, `dashed`, `dotted`.

### Icon Circle

| Control | Type | Default/Global |
|---------|------|----------------|
| Circle size | `Controls_Manager::SLIDER` (px) | `44px` |
| Background color (default) | `Controls_Manager::COLOR` | `Global_Colors::COLOR_PRIMARY` |
| Background color (scrolled) | `Controls_Manager::COLOR` | `Global_Colors::COLOR_PRIMARY` |
| Border color | `Controls_Manager::COLOR` | `transparent` |
| Border width | `Controls_Manager::SLIDER` (px) | `0px` |
| Icon color (default) | `Controls_Manager::COLOR` | `#ffffff` |
| Icon color (scrolled) | `Controls_Manager::COLOR` | `#ffffff` |
| Icon size | `Controls_Manager::SLIDER` (px) | `20px` |

### Card

| Control | Type | Default |
|---------|------|---------|
| Background color | `Controls_Manager::COLOR` | `#f5f5f5` |
| Border radius | `Controls_Manager::DIMENSIONS` (px) | `8px` |
| Box shadow | `Group_Control_Box_Shadow` | `0 2px 8px rgba(0,0,0,0.1)` |
| Padding | `Controls_Manager::DIMENSIONS` | `0px` (image bleeds to edge) |

### Connector Arrow

| Control | Type | Default |
|---------|------|---------|
| Size | `Controls_Manager::SLIDER` (px) | `12px` |

> **Note**: Color automatically inherits from card background color.

### Label

| Control | Type | Default/Global |
|---------|------|----------------|
| Typography | `Group_Control_Typography` | `Global_Typography::TYPOGRAPHY_TEXT` |
| Color | `Controls_Manager::COLOR` | `Global_Colors::COLOR_TEXT` |
| Spacing from icon | `Controls_Manager::SLIDER` (px) | `12px` |

### Image

| Control | Type | Default |
|---------|------|---------|
| Aspect ratio | `Controls_Manager::SELECT` | `16:9` |

> **Options**: 16:9, 4:3, 1:1, Original, Custom (with width/height inputs)

### Layout

| Control | Type | Default |
|---------|------|---------|
| Mode | `Controls_Manager::SELECT` | `Alternate` |
| Starting side | `Controls_Manager::SELECT` | `Left` |
| Item gap | `Controls_Manager::SLIDER` (px) | `48px` |

---

## Scroll Progress Behavior

### Overview

The timeline line fills progressively as the user scrolls, providing visual feedback of progress through the content.

### Formula

```javascript
const viewportMiddle = window.scrollY + (window.innerHeight / 2);
const widgetRect = widget.getBoundingClientRect();
const widgetTop = window.scrollY + widgetRect.top;
const widgetHeight = widgetRect.height;

let progress = (viewportMiddle - widgetTop) / widgetHeight;
progress = Math.max(0, Math.min(1, progress)); // clamp to 0-1
```

- When viewport middle reaches widget top: `progress = 0%`
- When viewport middle reaches widget bottom: `progress = 100%`
- Linear interpolation between these points

### Icon Transitions

Each icon transitions to "scrolled" state when the progress line reaches its vertical position:

```javascript
items.forEach((item) => {
    const iconRect = item.querySelector('.timeline-icon').getBoundingClientRect();
    const iconCenterY = window.scrollY + iconRect.top + (iconRect.height / 2);
    const iconProgress = (iconCenterY - widgetTop) / widgetHeight;

    if (progress >= iconProgress) {
        item.classList.add('is-passed');
    } else {
        item.classList.remove('is-passed');
    }
});
```

### Implementation Notes

- Use `requestAnimationFrame` for smooth performance
- Use passive scroll event listener: `{ passive: true }`
- Store progress in CSS custom property: `--timeline-progress`
- Icon state controlled via `.is-passed` class

---

## Technical Requirements

### Elementor Integration

Widget class must implement:

```php
class Timeline extends \Elementor\Widget_Base {

    public function get_name(): string {
        return 'msshext-visual-timeline';
    }

    public function get_title(): string {
        return esc_html__('Visual Timeline', 'msshext');
    }

    public function get_icon(): string {
        return 'eicon-time-line';
    }

    public function get_categories(): array {
        return ['msshext'];
    }

    public function get_keywords(): array {
        return ['timeline', 'schedule', 'history', 'events'];
    }

    public function get_script_depends(): array {
        return ['msshext-timeline'];
    }

    public function get_style_depends(): array {
        return ['msshext-timeline'];
    }

    protected function register_controls(): void {
        // Content controls (TAB_CONTENT)
        // Style controls (TAB_STYLE)
    }

    protected function render(): void {
        // PHP render output
    }

    protected function content_template(): void {
        // Backbone.js template for editor preview
    }
}
```

### Script Registration

In main plugin file or module:

```php
add_action('wp_enqueue_scripts', function() {
    wp_register_script(
        'msshext-timeline',
        MSSHEXT_ASSETS_URL . 'js/timeline.js',
        ['elementor-frontend'],
        MSSHEXT_VERSION,
        true
    );

    wp_register_style(
        'msshext-timeline',
        MSSHEXT_ASSETS_URL . 'css/timeline.css',
        [],
        MSSHEXT_VERSION
    );
});
```

### JavaScript Handler Pattern

```javascript
(function($) {
    'use strict';

    const TimelineHandler = function($scope) {
        const $widget = $scope.find('.msshext-timeline');
        if (!$widget.length) return;

        // Initialize scroll progress tracking
        const updateProgress = () => {
            // ... progress calculation
            requestAnimationFrame(updateProgress);
        };

        // Use Intersection Observer for performance
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    window.addEventListener('scroll', onScroll, { passive: true });
                } else {
                    window.removeEventListener('scroll', onScroll);
                }
            });
        });

        observer.observe($widget[0]);
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/msshext-visual-timeline.default',
            TimelineHandler
        );
    });
})(jQuery);
```

### CSS Custom Properties

```css
.msshext-timeline {
    --timeline-progress: 0;
    --line-color: var(--e-global-color-primary, #1a3a5c);
    --line-color-progress: var(--e-global-color-secondary, #1a3a5c);
    --icon-bg: var(--e-global-color-primary, #1a3a5c);
    --icon-bg-scrolled: var(--e-global-color-primary, #1a3a5c);
    --icon-color: #ffffff;
    --icon-color-scrolled: #ffffff;
}

.msshext-timeline-line::before {
    height: calc(var(--timeline-progress) * 100%);
    background-color: var(--line-color-progress);
}

.msshext-timeline-item.is-passed .msshext-timeline-icon {
    background-color: var(--icon-bg-scrolled);
    color: var(--icon-color-scrolled);
}
```

---

## File Structure

```
includes/elementor/modules/timeline/
├── module.php              # Module registration (extends Module_Base)
└── widgets/
    └── timeline.php        # Widget class (extends Widget_Base)

assets/
├── css/
│   └── timeline.css        # Base styles
└── js/
    └── timeline.js         # Scroll progress handler
```

### Module Pattern (Following Existing Plugin Structure)

```php
<?php
namespace MSSHEXT\Elementor\Modules\Timeline;

use MSSHEXT\Elementor\Base\Module_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Module extends Module_Base {

    public function get_widgets(): array {
        return ['Timeline'];
    }

    public function get_name(): string {
        return 'msshext-timeline';
    }
}
```

---

## Empty State

In Elementor editor, when no items are configured:
- Display placeholder message: **"Add timeline items to get started"**
- Placeholder styled to match Elementor's native empty widget states

---

## Edge Cases

| Scenario | Expected Behavior |
|----------|-------------------|
| **Single item** | Timeline displays with line, single card, connector, and icon (if set) |
| **No icon set** | Icon circle hidden; timeline line continues uninterrupted through that position |
| **Very long description** | Content expands naturally; no truncation |
| **Image load failure** | Show Elementor's default broken image placeholder |
| **Multiple timelines on page** | Each instance tracks scroll progress independently |
| **Widget in modal/popup** | Scroll progress tracks modal's scroll container if applicable |

---

## Behavior Notes

- **No animation**: Items do not animate on scroll (only line progress effect)
- **No click actions**: Clicking items does nothing (static display)
- **No min/max items**: Works with any number of items (1+)

---

## Acceptance Scenarios

### Layout

**Scenario 1: Alternate layout starting left**
```gherkin
Given: Layout mode is "Alternate" and starting side is "Left"
When: 4 items are added to the timeline
Then: Items 1 and 3 appear on left of timeline
  And: Items 2 and 4 appear on right of timeline
  And: Timeline line is centered
```

**Scenario 2: Alternate layout starting right**
```gherkin
Given: Layout mode is "Alternate" and starting side is "Right"
When: 3 items are added
Then: Items 1 and 3 appear on right
  And: Item 2 appears on left
```

**Scenario 3: Left-aligned layout**
```gherkin
Given: Layout mode is "Left-aligned"
When: Items are added
Then: Timeline line appears on left edge
  And: All cards appear on right side
```

**Scenario 4: Mobile responsive collapse**
```gherkin
Given: Any layout mode configured
When: Viewport width is below Elementor's mobile breakpoint
Then: Layout collapses to single column
  And: Timeline appears on left
  And: All cards appear on right
```

### Scroll Progress

**Scenario 5: Progress at widget entry**
```gherkin
Given: A timeline widget on the page
When: Viewport middle reaches widget top edge
Then: Progress line shows 0% filled
  And: No icons are in scrolled state
```

**Scenario 6: Progress at widget middle**
```gherkin
Given: A timeline widget with 4 evenly-spaced items
When: Viewport middle is at 50% of widget height
Then: Progress line shows ~50% filled
  And: Top 2 icons display scrolled state colors
  And: Bottom 2 icons display default state colors
```

**Scenario 7: Progress at widget exit**
```gherkin
Given: A timeline widget on the page
When: Viewport middle reaches widget bottom edge
Then: Progress line shows 100% filled
  And: All icons are in scrolled state
```

### Icons & Labels

**Scenario 8: Item without icon**
```gherkin
Given: A timeline item with no icon configured
When: Timeline renders
Then: No icon circle appears for that item
  And: Timeline line continues uninterrupted
  And: Label (if set) still displays
```

**Scenario 9: Item with custom SVG icon**
```gherkin
Given: A timeline item with uploaded SVG as icon
When: Timeline renders
Then: SVG displays within icon circle
  And: SVG respects configured icon size
  And: SVG color follows icon color setting
```

**Scenario 10: Item with label**
```gherkin
Given: A timeline item with label "Dopoledne"
  And: Card is positioned on left side
When: Timeline renders
Then: Label "Dopoledne" appears on right side of icon
  And: Label is vertically aligned with icon center
```

### Edge Cases

**Scenario 11: Single item timeline**
```gherkin
Given: Only 1 item configured in timeline
When: Timeline renders
Then: Single card displays with connector
  And: Timeline line displays (minimal height)
  And: Icon displays (if configured)
```

**Scenario 12: Empty state in editor**
```gherkin
Given: Widget added to page in Elementor editor
  And: No items configured in repeater
When: Editor preview renders
Then: Placeholder message displays: "Add timeline items to get started"
```

---

## Reference

The widget visual design is based on the reference screenshot showing:
- Kindergarten daily schedule ("Jak to u nas vypada a co cely den deti delaji")
- Cards with landscape images and titles ("Hry", "Pohybova chvilka")
- Dark blue timeline line with circular icons
- Labels ("Dopoledne") positioned on timeline
- Light gray card backgrounds with subtle shadows
- Connector arrows pointing from cards to timeline

---

## Sources

- [Elementor Color Control Documentation](https://developers.elementor.com/docs/editor-controls/control-color/)
- [Elementor Widget Controls](https://developers.elementor.com/docs/widgets/widget-controls/)
- [Elementor Global Colors & Typography](https://github.com/elementor/elementor-developers-docs/blob/master/src/editor-controls/global-style.md)
- [Elementor Repeater Control](https://developers.elementor.com/docs/editor-controls/control-repeater/)
- [Elementor Widget Scripts](https://developers.elementor.com/docs/widgets/widget-dependencies/)
- Existing plugin patterns: `includes/elementor/modules/events/`
