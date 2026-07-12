<div align="center">
<img src="logo.png" width="80" height="80" style="border-radius:50%">

### Spark Studio 17
**Visual IDE for building PHP desktop applications. Originally DevelNext.**

_*Now available on Linux🐧*_

[![Version](https://img.shields.io/badge/version-17.0.1-6366f1?style=flat-square)]()
[![Runtime](https://img.shields.io/badge/runtime-JPHP%20%2B%20JavaFX-ff6600?style=flat-square)]()
[![License](https://img.shields.io/badge/license-MIT-22c55e?style=flat-square)]()
[![Platform](https://img.shields.io/badge/platform-Java%208%2B-informational?style=flat-square)]()
</div>

## Overview

<details>
  <summary>Screenshots</summary>
  
  | Screenshots | 
  | :--:  | 
  | <img width="1920" height="1031" alt="image1" src="https://github.com/user-attachments/assets/d1a0896c-ed6b-4ada-90c5-8bc7c1c33ddc" /> | 
  | <img width="1920" height="1030" alt="image2" src="https://github.com/user-attachments/assets/38b77f55-7376-4154-8b22-96ef4ab31cee" /> |
  | <img width="1920" height="1031" alt="image3" src="https://github.com/user-attachments/assets/0ac372d2-b205-4e82-9597-3c59d1d6cdba" /> |
  
</details>   

Spark Studio is an IDE for creating cross-platform desktop GUI applications in PHP. It bundles the JPHP runtime — a PHP implementation on the Java Virtual Machine — with a visual form designer, code editor, debugger, plugin system, and build toolchain (Launch4J, InnoSetup, Ant, Gradle).

## Features

- Visual form designer with drag-and-drop palette, property editor, event editor, behavior editor
- PHP code editor with syntax highlighting, autocomplete, code navigation
- Project templates (GUI app, PHP library, Gradle, etc.)
- Visual behaviors: animations, effects, game logic
- Debugger with breakpoints and step-through
- Plugin system via extensions and bundles
- Export to JAR with .bat executor
- English and Russian interfaces

## Quick start

```bash
./bin/windows.bat #windows
```

```bash
./bin/linux.sh #linux
```

> [!NOTE]
> The Linux version was tested on:
> 
> <img width="128" height="128" alt="arch" src="https://github.com/user-attachments/assets/2d6d8e9b-992f-4a22-b4b2-81f8ef19a4eb" /> <img width="128" height="128" alt="cachy" src="https://github.com/user-attachments/assets/169be5bb-9182-44ec-ac98-f07bd851a143" /> <img width="128" height="128" alt="endeavour" src="https://github.com/user-attachments/assets/66e9c7d9-2ed3-42b1-8316-5fb76e6f8d95" /> <img width="128" height="128" alt="nix" src="https://github.com/user-attachments/assets/aeb0e0a8-eca8-4b30-90c7-f79f0ef353a1" />






> [!WARNING]
> Recommend running Spark Studio on Linux with PortProton or Wine, because the project is not natively supported on Linux distributions. If you encounter problems with the tested distributions, create an issue using the template. Requires Java 8+.

> [!NOTE]
> To run Spark Studio natively on Linux:
> 1. Download and install Java from the [official website](https://www.java.com/download/).
> 2. Move the files to the /bin directory and name the folder "__jre__" (The path to java should look like this "__./bin/jre/bin/java__").
> 3. Launch Spark Studio.

> [!WARNING]
> If Spark Studio doesn't launched on linux natively, try to download JFX into the jdk directory or launch with PortProton or Wine.

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
