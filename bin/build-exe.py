#!/usr/bin/env python3
"""
Spark Studio — Windows Distribution Builder

Builds SparkStudio.exe from C source and assembles a distribution package.

Usage:
    python3 bin/build-exe.py             # compile EXE only
    python3 bin/build-exe.py --dist      # compile + assemble dist/
    python3 bin/build-exe.py --installer # compile + dist + InnoSetup script
"""

import os
import shutil
import subprocess
import sys
import argparse

PROJECT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BIN     = os.path.join(PROJECT, "bin")
DIST    = os.path.join(PROJECT, "dist", "Spark Studio")

COMPILER   = "x86_64-w64-mingw32-gcc"
WINDRES    = "x86_64-w64-mingw32-windres"
CFLAGS     = ["-O2", "-s"]


def _run(cmd, desc):
    r = subprocess.run(cmd, capture_output=True, text=True)
    if r.returncode != 0:
        print(f"[!] {desc} failed:\n{r.stderr}")
        return False
    return True


def compile_exe():
    """Cross-compile SparkStudio.exe from launcher.c + icon resource."""
    src  = os.path.join(BIN, "launcher.c")
    rc   = os.path.join(BIN, "launcher.rc")
    res  = os.path.join(BIN, "launcher.res")
    dst  = os.path.join(BIN, "SparkStudio.exe")

    for f in [src]:
        if not os.path.isfile(f):
            print(f"[!] Source not found: {f}")
            return False

    # Compile resource file
    if os.path.isfile(rc):
        cmd_rc = [WINDRES, "-O", "coff", "-i", rc, "-o", res]
        if not _run(cmd_rc, "resource compilation"):
            return False
        objs = [src, res]
    else:
        objs = [src]

    # Compile
    cmd = [COMPILER] + CFLAGS + ["-o", dst] + objs
    r = subprocess.run(cmd, capture_output=True, text=True)
    if r.returncode != 0:
        print(f"[!] Compilation failed:\n{r.stderr}")
        return False

    # Clean up .res
    if os.path.isfile(res):
        os.unlink(res)

    size = os.path.getsize(dst)
    print(f"[✓] SparkStudio.exe created ({size // 1024} KB)")
    return True


def assemble_dist():
    """Assemble a distribution folder with the IDE and bundled JRE."""
    dist_lib = os.path.join(DIST, "lib")
    dist_bin = os.path.join(DIST, "bin")
    dist_jre = os.path.join(dist_bin, "jre")

    print(f"[*] Assembling distribution → {DIST}")

    # Clean & create
    if os.path.isdir(DIST):
        shutil.rmtree(DIST)

    # Copy launcher EXE
    os.makedirs(dist_bin, exist_ok=True)
    exe_src = os.path.join(BIN, "SparkStudio.exe")
    if os.path.isfile(exe_src):
        shutil.copy2(exe_src, os.path.join(dist_bin, "SparkStudio.exe"))
        print(f"  ✓ bin/SparkStudio.exe")

    # Copy JRE
    jre_src = os.path.join(BIN, "jre")
    if os.path.isdir(jre_src):
        shutil.copytree(jre_src, dist_jre, symlinks=False,
                        ignore=shutil.ignore_patterns("*.log"))
        print(f"  ✓ bin/jre/ (bundled)")

    # Copy source tree modules — these are our classpath entries
    module_dirs = [
        "runtime/jphp-core", "runtime/jphp-runtime",
        "gui/jphp-gui-ext", "gui/jphp-gui-richtext-ext",
        "gui/jphp-desktop-ext", "gui/jphp-systemtray-ext",
        "gui/spark-designer", "gui/reactfx-M5",
        "gui/richtextfx", "gui/undofx", "gui/wellbehavedfx",
        "parser/antlr4-runtime", "parser/spark-lexer", "parser/jphp-parser",
        "extensions/jphp-json-ext", "extensions/jphp-xml-ext",
        "extensions/jphp-zend-ext", "extensions/jphp-zip-ext",
        "database/HikariCP-java6", "database/jphp-sql-ext",
        "debug/jphp-debugger", "network/flowless",
        "utils/asm-all", "utils/commons-codec", "utils/gson",
        "utils/highlights", "utils/javassist-GA", "utils/slf4j-api",
        "utils/zt-zip",
        "framework/SparkStudio", "framework/jphp-app-framework",
    ]

    for rel in module_dirs:
        src = os.path.join(PROJECT, rel)
        dst = os.path.join(DIST, rel)
        if os.path.isdir(src):
            os.makedirs(os.path.dirname(dst), exist_ok=True)
            shutil.copytree(src, dst, symlinks=False,
                            ignore=shutil.ignore_patterns(".git"))
        else:
            print(f"  ! skipped (not found): {rel}")

    # Copy root files
    for f in ["launcher.jar", "logo.png", "projectExtension.ico"]:
        s = os.path.join(PROJECT, f)
        if os.path.isfile(s):
            shutil.copy2(s, os.path.join(DIST, f))

    # Copy language packs
    langs_src = os.path.join(PROJECT, "languages")
    if os.path.isdir(langs_src):
        shutil.copytree(langs_src, os.path.join(DIST, "languages"),
                        symlinks=False)

    # Copy ide/ docs
    ide_src = os.path.join(PROJECT, "ide")
    if os.path.isdir(ide_src):
        shutil.copytree(ide_src, os.path.join(DIST, "ide"),
                        symlinks=False)

    print(f"[✓] Distribution assembled: {DIST}")
    return True


def write_iss():
    """Write InnoSetup script for installer creation."""
    iss_path = os.path.join(PROJECT, "dist", "spark-studio.iss")
    os.makedirs(os.path.dirname(iss_path), exist_ok=True)

    content = """\
; Spark Studio — InnoSetup installer script
; Generated by bin/build-exe.py

#define MyAppName "Spark Studio"
#define MyAppVersion "17.0.1"
#define MyAppPublisher "ll1ness"
#define MyAppURL "https://github.com/ll1ness/SparkStudio"
#define MyAppExeName "bin\\SparkStudio.exe"

[Setup]
AppId={{A1B2C3D4-E5F6-7890-ABCD-EF1234567890}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}
AppPublisherURL={#MyAppURL}
DefaultDirName={autopf}\\{#MyAppName}
DefaultGroupName={#MyAppName}
AllowNoIcons=yes
OutputDir=.
OutputBaseFilename=SparkStudio-{#MyAppVersion}-Setup
Compression=lzma2/max
SolidCompression=yes
UninstallDisplayIcon={app}\\bin\\SparkStudio.exe
PrivilegesRequired=admin

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"
Name: "russian"; MessagesFile: "compiler:Languages\\Russian.isl"

[Tasks]
Name: "desktopicon"; Description: "Create a &desktop shortcut"; GroupDescription: "Additional icons:"

[Files]
Source: "Spark Studio\\*"; DestDir: "{app}"; Flags: ignoreversion recursesubdirs createallsubdirs

[Icons]
Name: "{group}\\{#MyAppName}"; Filename: "{app}\\{#MyAppExeName}"
Name: "{group}\\Uninstall {#MyAppName}"; Filename: "{uninstallexe}"
Name: "{commondesktop}\\{#MyAppName}"; Filename: "{app}\\{#MyAppExeName}"; Tasks: desktopicon

[Run]
Filename: "{app}\\{#MyAppExeName}"; Description: "Launch {#MyAppName}"; Flags: nowait postinstall skipifsilent
"""
    with open(iss_path, "w", encoding="utf-8") as f:
        f.write(content)

    print(f"[✓] InnoSetup script: {iss_path}")
    return True


def main():
    parser = argparse.ArgumentParser(description="Spark Studio Windows Distribution Builder")
    parser.add_argument("--dist", action="store_true", help="Assemble distribution folder")
    parser.add_argument("--installer", action="store_true", help="Also write InnoSetup .iss script")
    args = parser.parse_args()

    if not compile_exe():
        sys.exit(1)

    if args.dist or args.installer:
        assemble_dist()

    if args.installer:
        write_iss()

    print("\n[*] Done.")


if __name__ == "__main__":
    main()
