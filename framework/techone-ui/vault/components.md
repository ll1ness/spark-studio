# TechOne UI — Components

All 24 components use the `to-` prefix for CSS classes.

## Basic

| Component | CSS class | JS class | Description |
|---|---|---|---|
| Button | `.to-button` | `toui.button` | Variants: primary, secondary, outline |
| IconButton | `.to-icon-button` | `toui.iconButton` | Icon-only button |
| Tag | `.to-tag` | `toui.tag` | Labels and tags |
| Badge | `.to-badge` | `toui.badge` | Status badges (success, warning, danger) |
| Avatar | `.to-avatar` | `toui.avatar` | User avatars |

## Layout

| Component | CSS class | JS class | Description |
|---|---|---|---|
| Card | `.to-card` | `toui.card` | Content cards |
| Grid | `.to-grid` | `toui.grid` | CSS grid with data-columns |
| Flex | `.to-flex` | `toui.flex` | Flexbox container |
| Banner | `.to-banner` | `toui.banner` | Info banners with variants |
| Breadcrumbs | `.to-breadcrumbs` | `toui.breadcrumbs` | Navigation breadcrumbs |

## Interactive

| Component | CSS class | JS class | Description |
|---|---|---|---|
| Accordion | `.to-accordion` | `toui.accordion` | Collapsible panels |
| Dialog | `.to-dialog-wrapper` | `toui.dialog` | Modal windows |
| Dropdown | `.to-dropdown` | `toui.dropdown` | Dropdown menus with selection |
| Tooltip | `.to-tooltip` | `toui.tooltip` | Hover tooltips |
| ToggleButton | `.to-toggle-button` | `toui.toggleButton` | Toggle buttons and groups |

## Status

| Component | CSS class | JS class | Description |
|---|---|---|---|
| Spinner | `.to-spinner` | `toui.spinner` | Loading spinners |
| Skeleton | `.to-skeleton` | `toui.skeleton` | Skeleton loaders |
| Progress | `.to-progress` | `toui.progress` | Progress bars with animation |
| StatusIndicator | `.to-status-indicator` | `toui.statusIndicator` | Status lights |
| Pulse | `.to-pulse` | `toui.pulse` | Pulsing indicator |

## Utility

| Component | CSS class | JS class | Description |
|---|---|---|---|
| Table | `.to-table` | `toui.table` | Data tables |
| Timeline | `.to-timeline` | `toui.timeline` | Vertical timeline |
| ScrollTop | `.to-scroll-top` | `toui.scrollTop` | Scroll-to-top button |
| Icon | `.to-icon` | `toui.icon` | Inline SVG icons |

## Data attributes

Components use `data-*` attributes for configuration:

- `data-variant` — visual variant (primary, success, danger, etc.)
- `data-size` — size (s, m, l)
- `data-open` — toggle state (true/false)
- `data-selected` — selection state (true/false)
- `data-value` / `data-max` — progress values
- `data-dialog` — dialog trigger binding (matches dialog id)
- `data-position` — tooltip position (top, bottom, left, right)
