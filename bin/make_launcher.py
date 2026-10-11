#!/usr/bin/env python3
"""
Собирает bin/spark-launcher.jar и bin/SparkStudio.exe для Spark Studio.

Этапы:
  1. компилирует bin/src/SparkLauncher.java (javac --release 8) — тонкий
     лаунчер: распаковка binary.jar рядом с exe и запуск IDE в этом же JVM,
  2. пакует bin/spark-launcher.jar (Main-Class: SparkLauncher),
  3. вызывает launch4jc (bin/launch4j.xml) и получает bin/SparkStudio.exe.

Требуются: JDK (javac) и Launch4j (launch4jc.exe). Пути ищутся автоматически
в JAVA_HOME / Program Files, их можно указать явно: --javac <path> --launch4j <path>.

Рядом с SparkStudio.exe должны лежать spark-launcher.jar и binary.jar
(лаунчер распаковывает binary.jar в app-<hash> рядом с exe; данные
пользователя пишутся в каталог данных приложения).

Использование:
    python bin/make_launcher.py                 # собрать jar + exe
    python bin/make_launcher.py --check         # только показать найденные пути
"""

import argparse
import os
import re
import shutil
import subprocess
import sys
import tempfile
import zipfile
from datetime import datetime
from pathlib import Path

BIN_DIR = Path(__file__).resolve().parent
PROJECT_ROOT = BIN_DIR.parent
SRC = BIN_DIR / "src" / "SparkLauncher.java"
CLASSES_DIR = BIN_DIR / ".launcher-classes"
LAUNCHER_JAR = BIN_DIR / "spark-launcher.jar"
LAUNCH4J_CONFIG = BIN_DIR / "launch4j.xml"
BUILD_CONFIG = BIN_DIR / ".launch4j-build.xml"
EXE = BIN_DIR / "SparkStudio.exe"

# Ресурсы, которые launch4j встраивает в exe через windres. windres не понимает
# не-ASCII пути, поэтому при CLI-сборке они при необходимости копируются в
# ASCII-каталог (%TEMP%) и путь подставляется в конфиг.
ICON = BIN_DIR / "favicon.ico"
SPLASH = BIN_DIR / "spark_studio_splash17.bmp"

MAIN_CLASS = "SparkLauncher"


def log(msg: str) -> None:
    print(f"[{datetime.now():%Y-%m-%d %H:%M:%S}] {msg}")


def error(msg: str) -> None:
    print(f"[ERROR] {msg}", file=sys.stderr)
    sys.exit(1)


def find_bin(name: str) -> str:
    """Ищет исполняемый файл: JAVA_HOME/bin, PATH, Program Files (JDK/Launch4j)."""
    exe = name + (".exe" if os.name == "nt" else "")

    found = shutil.which(name)
    if found:
        return found

    candidates = []

    java_home = os.environ.get("JAVA_HOME")
    if java_home:
        candidates.append(str(Path(java_home) / "bin" / exe))

    if os.name == "nt":
        for base in (Path(r"C:\Program Files"), Path(r"C:\Program Files (x86)")):
            if not base.is_dir():
                continue
            for d in base.iterdir():
                if d.is_dir():
                    candidates.append(str(d / "bin" / exe))
                    if name == "launch4jc":
                        candidates.append(str(d / "launch4jc.exe"))
                    if name == "javac" and (d / "bin" / "javac.exe").is_file():
                        candidates.append(str(d / "bin" / "javac.exe"))
    else:
        for d in (Path("/usr/lib/jvm"), Path("/opt")):
            if d.is_dir():
                for sub in d.iterdir():
                    c = sub / "bin" / exe
                    if c.is_file():
                        candidates.append(str(c))

    for c in candidates:
        p = Path(c)
        if p.is_dir():
            continue
        if p.is_file():
            return str(p)

    if name == "launch4jc" and os.name == "nt":
        # часто ставят в Program Files (x86)\Launch4j
        for root in (Path(r"C:\Program Files (x86)"), Path(r"C:\Program Files")):
            c = root / "Launch4j" / "launch4jc.exe"
            if c.is_file():
                return str(c)

    return ""


def compile_launcher(javac: str) -> list:
    if CLASSES_DIR.exists():
        shutil.rmtree(CLASSES_DIR, ignore_errors=True)
    CLASSES_DIR.mkdir(parents=True, exist_ok=True)

    log(f"javac: {javac}")
    cmd = [javac, "--release", "8", "-encoding", "UTF-8",
           "-d", str(CLASSES_DIR), str(SRC)]
    result = subprocess.run(cmd, capture_output=True, text=True, errors="replace")
    if result.returncode != 0:
        print(result.stdout)
        print(result.stderr, file=sys.stderr)
        error("Не удалось скомпилировать SparkLauncher.java")
    return list(CLASSES_DIR.rglob("*.class"))


def pack_launcher(classes: list) -> None:
    if LAUNCHER_JAR.exists():
        LAUNCHER_JAR.unlink()

    manifest = ("Manifest-Version: 1.0\n"
                f"Main-Class: {MAIN_CLASS}\n")

    with zipfile.ZipFile(str(LAUNCHER_JAR), "w", zipfile.ZIP_STORED) as zf:
        zf.writestr("META-INF/MANIFEST.MF", manifest)
        for cls in classes:
            zf.write(str(cls), cls.relative_to(CLASSES_DIR).as_posix())

    log(f"готово: {LAUNCHER_JAR} ({len(classes)} class, Main-Class: {MAIN_CLASS})")


def ascii_asset(src: Path, tmp_name: str) -> str:
    """Путь к ассету, пригодный для windres: копирует в ASCII-каталог при необходимости."""
    if not src.is_file():
        return ""
    try:
        str(src).encode("ascii")
        return str(src)
    except UnicodeEncodeError:
        # windres внутри launch4j не переваривает не-ASCII пути —
        # кладём копию в ASCII-каталог (%TEMP%, ~/.cache и т.п.)
        dst = Path(tempfile.gettempdir()) / tmp_name
        try:
            shutil.copyfile(src, dst)
            return str(dst)
        except OSError:
            return ""


def build_exe(launch4jc: str) -> None:
    log(f"launch4jc: {launch4jc}")
    if EXE.exists():
        EXE.unlink()

    # подставляем ASCII-пути иконки и сплэша (сам XML задаёт относительные пути)
    text = LAUNCH4J_CONFIG.read_text(encoding="utf-8")

    icon = ascii_asset(ICON, "spark-studio-icon.ico")
    if icon:
        text = re.sub(r"<icon>.*?</icon>", lambda m: f"<icon>{icon}</icon>", text, count=1)
        log(f"icon: {icon}")
    else:
        log("icon: не найден (используется по умолчанию launch4j)")

    splash = ascii_asset(SPLASH, "spark-studio-splash.bmp")
    if splash:
        text = re.sub(r"(<splash>\s*<file>).*?(</file>)",
                      lambda m: m.group(1) + splash + m.group(2), text, count=1)
        log(f"splash: {splash}")

    BUILD_CONFIG.write_text(text, encoding="utf-8")

    try:
        result = subprocess.run([launch4jc, str(BUILD_CONFIG)],
                                capture_output=True, text=True, errors="replace")
        if result.stdout:
            print(result.stdout)
        if result.stderr:
            print(result.stderr, file=sys.stderr)
        if result.returncode != 0:
            error("launch4jc: не удалось собрать SparkStudio.exe")
    finally:
        BUILD_CONFIG.unlink(missing_ok=True)

    if not EXE.is_file():
        error(f"{EXE} не создан")

    size_mb = EXE.stat().st_size / (1024 * 1024)
    log(f"готово: {EXE} ({size_mb:.1f} MB)")


def main() -> None:
    parser = argparse.ArgumentParser(description="Сборка SparkStudio.exe (launch4j)")
    parser.add_argument("--javac", help="путь к javac")
    parser.add_argument("--launch4j", help="путь к launch4jc")
    parser.add_argument("--check", action="store_true", help="только показать пути")
    parser.add_argument("-s", "--skip-exe", action="store_true", help="только spark-launcher.jar")
    args = parser.parse_args()

    print("=" * 60)
    print("Spark Studio — exe builder (launch4j)")
    print(f"Project: {PROJECT_ROOT}")
    print("=" * 60)

    javac = args.javac or (BIN_DIR.parent / "jdk" / "bin" / ("javac.exe" if os.name == "nt" else "javac")) or ""
    if not Path(javac).is_file():
        javac = find_bin("javac")
    if not javac:
        error("javac не найден. Установите JDK или передайте --javac <path>")

    launch4jc = ""
    if not args.skip_exe:
        launch4jc = args.launch4j or ""
        if launch4jc and not Path(launch4jc).is_file():
            launch4jc = ""
        if not launch4jc:
            launch4jc = find_bin("launch4jc")
        if not launch4jc:
            error("launch4jc не найден. Установите Launch4j или передайте --launch4j <path>")

    if args.check:
        print(f"  javac      : {javac}")
        print(f"  launch4jc  : {launch4jc or '(не требуется: --skip-exe)'}")
        print(f"  source     : {SRC}")
        print(f"  launcher   : {LAUNCHER_JAR}")
        print(f"  config     : {LAUNCH4J_CONFIG}")
        print(f"  outfile    : {EXE}")
        return

    classes = compile_launcher(javac)
    pack_launcher(classes)
    if not args.skip_exe:
        build_exe(launch4jc)


if __name__ == "__main__":
    main()