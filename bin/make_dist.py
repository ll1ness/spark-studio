#!/usr/bin/env python3
"""
Собирает готовый дистрибутив Spark Studio в каталог build/.

Пайплайн:
  1. bin/binary.jar        — classpath проекта + корневые runtime-ресурсы
                             (logo.png и т.п. из ROOT_FILES),
  2. bin/spark-launcher.jar — тонкий Java-лаунчер (распаковка + запуск IDE),
  3. bin/SparkStudio.exe   — exe (launch4j, dontWrapJar),
  4. build/                — самодостаточный дистрибутив:
        SparkStudio.exe
        spark-launcher.jar
        binary.jar
        logo.png               (переносимый ассет, лаунчер накладывает его
        projectExtension.ico    на распакованный рабочий корень)
        LICENSE, README.md

Рядом с exe исходников нет, но IDE при первом запуске распаковывается в
build/app-<hash> (хранится максимум 3 версии). Данные пользователя
(config, log, cache, library, projects) пишутся в каталог данных
(AppData / ~/Library / ~/.local/share).

Требуются: JDK (javac) и Launch4j (launch4jc.exe) — как в make_launcher.py.

Использование:
    python bin/make_dist.py                 # собрать всё (jar + exe + build/)
    python bin/make_dist.py --no-rebuild    # не пересобирать binary.jar
    python bin/make_dist.py --skip-exe      # без launch4j (только jar + build/)
"""

import argparse
import shutil
import sys
from datetime import datetime
from pathlib import Path

import make_binary_jar as mbj
import make_launcher as ml

BIN_DIR = Path(__file__).resolve().parent
PROJECT_ROOT = BIN_DIR.parent
DIST_DIR = PROJECT_ROOT / "build"

# Ресурсы, которые лаунчер копирует из папки сборки в рабочий корень
# (должны совпадать с SparkLauncher.PORTABLE_ASSETS).
PORTABLE_ASSETS = ["logo.png"]

# Сопутствующие файлы дистрибутива (не обязательны для запуска).
EXTRA_FILES = ["projectExtension.ico", "LICENSE", "README.md"]


def log(msg: str) -> None:
    print(f"[{datetime.now():%Y-%m-%d %H:%M:%S}] {msg}")


def error(msg: str) -> None:
    print(f"[ERROR] {msg}", file=sys.stderr)
    sys.exit(1)


def assemble(binary_jar: Path, launcher_jar: Path, exe: Path) -> None:
    log(f"сборка дистрибутива: {DIST_DIR}")
    if DIST_DIR.exists():
        shutil.rmtree(DIST_DIR, ignore_errors=True)
    DIST_DIR.mkdir(parents=True, exist_ok=True)

    files = [binary_jar, launcher_jar]
    if exe and exe.is_file():
        files.insert(0, exe)

    for f in files:
        if not f.is_file():
            error(f"нет файла для дистрибутива: {f}")
        shutil.copy2(f, DIST_DIR / f.name)
        log(f"  + {f.name}")

    for name in PORTABLE_ASSETS + EXTRA_FILES:
        src = PROJECT_ROOT / name
        if not src.exists():
            if name in PORTABLE_ASSETS:
                log(f"  ! пропущен ресурс: {name}")
            continue
        dst = DIST_DIR / src.name
        if src.is_dir():
            shutil.copytree(src, dst)
        else:
            shutil.copy2(src, dst)
        log(f"  + {name}")

    total_mb = sum(p.stat().st_size for p in DIST_DIR.rglob("*") if p.is_file()) / (1024 * 1024)
    log(f"готово: {DIST_DIR} ({total_mb:.1f} MB)")


def main() -> None:
    parser = argparse.ArgumentParser(description="Собрать дистрибутив Spark Studio в build/")
    parser.add_argument("--javac", help="путь к javac")
    parser.add_argument("--launch4j", help="путь к launch4jc")
    parser.add_argument("--no-rebuild", action="store_true",
                        help="использовать существующий bin/binary.jar")
    parser.add_argument("-s", "--skip-exe", action="store_true",
                        help="не собирать exe (только jar-ы и build/)")
    args = parser.parse_args()

    print("=" * 60)
    print("Spark Studio — dist builder")
    print(f"Project: {PROJECT_ROOT}")
    print("=" * 60)

    # 1. binary.jar -----------------------------------------------------
    binary_jar = BIN_DIR / "binary.jar"
    if args.no_rebuild and binary_jar.is_file():
        log(f"binary.jar не пересобирается: {binary_jar}")
    else:
        log("сборка binary.jar:")
        mbj.build(binary_jar, mbj.ALL_DIRS)

    # 2. spark-launcher.jar (+ exe) ------------------------------------
    javac = args.javac or ml.find_bin("javac")
    if not javac:
        error("javac не найден. Установите JDK или передайте --javac <path>")

    launch4jc = ""
    if not args.skip_exe:
        launch4jc = args.launch4j or ml.find_bin("launch4jc")
        if not launch4jc:
            error("launch4jc не найден. Установите Launch4j или передайте --launch4j <path>")

    classes = ml.compile_launcher(javac)
    ml.pack_launcher(classes)

    exe = None
    if not args.skip_exe:
        ml.build_exe(launch4jc)
        exe = ml.EXE

    # 3. build/ ---------------------------------------------------------
    assemble(binary_jar, ml.LAUNCHER_JAR, exe)


if __name__ == "__main__":
    main()