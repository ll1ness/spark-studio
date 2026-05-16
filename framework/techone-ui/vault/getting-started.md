# TechOne UI — Getting Started

## Installation

Clone the repo:

```bash
git clone https://github.com/ll1ness/techone-ui.git
cd techone-ui
```

## Usage

Link the CSS and JS in your HTML:

```html
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My App</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <button class="to-button" data-variant="primary">Click me</button>
  <script src="techon-ui.min.js"></script>
</body>
</html>
```

That's it. All components auto-initialize on page load.

## Manual initialization

If you add components dynamically, call:

```js
toui.init();
```

## Using the `toui` namespace

Access component classes and systems:

```js
// component classes
const btn = new toui.button(el);
const dlg = new toui.dialog(el);

// animation system
toui.Animation.fadeIn(el);

// interaction helpers
toui.Interaction.handleClick(el, handler);

// state management
const store = toui.Management.createStore({ count: 0 });
```

## Building from source

```bash
npm install     # installs esbuild and serve
npm run build   # builds dist/techon-ui.min.js
npm run dev     # serves dist/ on port 3000
```
