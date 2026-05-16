<div align="center">
<img src="icon.png" width="64" height="64" alt="techone-logo">
  
### TechOne UI
_Open-source UI framework. Zero deps._

[![GitHub](https://img.shields.io/badge/GitHub-ll1ness-333?style=flat-square&logo=github)](https://github.com/ll1ness/techone-ui)
[![License](https://img.shields.io/badge/License-MIT-6366f1?style=flat-square)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Beta-f59e0b?style=flat-square)]()
[![Size](https://img.shields.io/badge/Size-70KB-0f0?style=flat-square)]()
[![Components](https://img.shields.io/badge/Components-24-f0f0f0?style=flat-square)]()
[![CDN](https://img.shields.io/badge/CDN-gitHub-333?style=flat-square)](https://github.com/ll1ness/techone-ui)
<img src="https://github.com/ll1ness/ll1ness/raw/legacy/latte.png" alt="dispatcher" width="550" />

[Install](#quick-start) · [Docs](vault/overview.md) · [Components](#components) · [GitHub](https://github.com/ll1ness/techone-ui)

</div>

24 UI components. One `.js` file. No build step. No npm install.

## Quick start

```bash
git clone https://github.com/ll1ness/techone-ui.git
cd techone-ui
python3 -m http.server 8080
```

Include in your project:

```html
<link rel="stylesheet" href="styles.css">
<script src="build/techon-ui.min.js"></script>
```

Then use any component:

```html
<button class="to-button" data-variant="primary">Click me</button>
```

## API

All components and systems are accessible via the `toui` namespace:

```js
toui.init()                        // initialize all components
toui.button                        // Button class
toui.accordion                     // Accordion class
toui.dialog                        // Dialog class
toui.Animation                     // animation utilities
toui.Interaction                   // interaction helpers
toui.Management                    // state management
```

## Components

| Category | Components |
|---|---|
| Basic | `button`, `icon-button`, `tag`, `badge`, `avatar` |
| Layout | `card`, `grid`, `flex`, `banner`, `breadcrumbs` |
| Interactive | `accordion`, `dialog`, `dropdown`, `tooltip`, `toggle-button` |
| Status | `spinner`, `skeleton`, `progress`, `status-indicator`, `pulse` |
| Utility | `timeline`, `table`, `scroll-top`, `icon` |

All 24 components are documented in [vault/components.md](vault/components.md).

## Customization

Override CSS variables:

```css
:root {
  --to-primary: #6366f1;
  --to-bg: #0a0a0f;
  --to-text: #ffffff;
}
```

## License

MIT © 2026 [ll1ness](https://github.com/ll1ness)

---

<div align="center">
  <a href="https://github.com/ll1ness/techone-ui">GitHub</a>
  · <a href="https://ll1ness.github.io/techone-ui/">Live Demo</a>
  · <a href="vault/overview.md">Docs</a>
</div>
