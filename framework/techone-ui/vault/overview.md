# TechOne UI — Overview

**TechOne UI** is a lightweight, zero-dependency UI framework with 24 components. Written in pure HTML, CSS, and JavaScript.

## Principles

- **Zero deps** — no npm, no build tools, no node_modules
- **Single file** — one `techon-ui.min.js` includes everything
- **Dark by default** — premium dark theme out of the box
- **Mobile-first** — responsive down to 320px
- **Accessible** — semantic HTML, ARIA, keyboard navigation

## File structure

```
techone-ui/
  index.html            demo site
  main.js               component loader
  styles.css            base styles + CSS variables
  techon-ui.min.js      compiled library (70KB)
  src/
    systems/            core modules
      init.js           auto-initialization
      animation.js      animation utilities
      interaction.js    event helpers
      management.js     state/store/events
      background.js     animated geometric background
    components/         24 component directories
      accordion/        index.html + component.js + component.css
      ...
  vault/                documentation
```

## Browser support

Chrome, Firefox, Safari, Edge — last 2 major versions.
