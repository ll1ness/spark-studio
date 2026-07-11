<div align="center">
<img src="logo.png" width="80" height="80" style="border-radius:50%">

### Spark Studio 17
**Visual IDE for building PHP desktop applications. Originally DevelNext.**

[![Version](https://img.shields.io/badge/version-16.7.5--autumn-6366f1?style=flat-square)]()
[![Runtime](https://img.shields.io/badge/runtime-JPHP%20%2B%20JavaFX-ff6600?style=flat-square)]()
[![License](https://img.shields.io/badge/license-MIT-22c55e?style=flat-square)]()
[![Platform](https://img.shields.io/badge/platform-Java%208%2B-informational?style=flat-square)]()
</div>

## Overview

Spark Studio is an IDE for creating cross-platform desktop GUI applications in PHP. It bundles the JPHP runtime — a PHP implementation on the Java Virtual Machine — with a visual form designer, code editor, debugger, plugin system, and build toolchain (Launch4J, InnoSetup, Ant, Gradle).

## Features

- Visual form designer with drag-and-drop palette, property editor, event editor, behavior editor
- PHP code editor with syntax highlighting, autocomplete, code navigation
- Project templates (GUI app, PHP library, Gradle, etc.)
- Visual behaviors: animations, effects, game logic
- Debugger with breakpoints and step-through
- Plugin system via extensions and bundles
- Export to JAR/EXE/MSI
- English and Russian interfaces

## Quick start

```bash
./bin/[OS].sh #ex. ./bin/linux.sh
```

Requires Java 8+ (bundled JRE in `bin/jre/`).

## Project structure

| Directory | Contents |
|---|---|
| `framework/SparkStudio/` | IDE core application (PHP) |
| `framework/techone-ui/` | TechOne UI design system (web components) |
| `framework/jphp-app-framework/` | JPHP application framework |
| `gui/` | JavaFX GUI extensions, form designer, rich text |
| `runtime/` | JPHP runtime and core (Java) |
| `extensions/` | JSON, XML, ZIP, Zend compat extensions |
| `ide/` | Documentation, platform support, plugin store |
| `languages/` | en / ru language packs |
| `bin/` | Launcher script, JRE, Wayland/X11 grab fix |

## Authors

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/meigoc">
        <img src="https://github.com/meigoc.png" width="80" height="80" style="border-radius:50%"><br>
        <b>meigoc</b>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/dim-s">
        <img src="https://github.com/dim-s.png" width="80" height="80" style="border-radius:50%"><br>
        <b>dim-s</b>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/SerafimArts">
        <img src="https://github.com/SerafimArts.png" width="80" height="80" style="border-radius:50%"><br>
        <b>SerafimArts</b>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/TsSaltan">
        <img src="https://github.com/TsSaltan.png" width="80" height="80" style="border-radius:50%"><br>
        <b>TsSaltan</b>
      </a>
    </td>
  </tr>
  <tr>
    <td align="center">
      <a href="https://github.com/nagayev">
        <img src="https://github.com/nagayev.png" width="80" height="80" style="border-radius:50%"><br>
        <b>nagayev</b>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/Ded-Alex">
        <img src="https://github.com/Ded-Alex.png" width="80" height="80" style="border-radius:50%"><br>
        <b>Ded-Alex</b>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/ll1ness">
        <img src="https://github.com/ll1ness.png" width="80" height="80" style="border-radius:50%"><br>
        <b>ll1ness</b>
      </a>
    </td>
  </tr>
</table>

## License

MIT © 2026 ll1ness. Other components under their respective licenses.
