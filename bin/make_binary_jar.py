#!/usr/bin/env python3
"""
Собирает bin/binary.jar для Spark Studio — единственный артефакт дистрибутива.

По умолчанию (--all) в jar попадает ВЕСЬ classpath проекта (та же структура,
что в build/temp_jar): каталоги кладутся как есть, поэтому после распаковки
это готовый рабочий корень (spark.path) с каталогами gui/, runtime/, ...,
languages/, ide/, php-sdk/.

Рядом с .exe/скриптом ничего, кроме binary.jar, не нужно: лаунчер
(bin/run.py) распаковывает jar в каталог данных пользователя и запускает
IDE оттуда. Все записи приложения идут в spark.data (AppData / ~/Library /
~/.local/share в зависимости от ОС), а не в каталог установки.

Кроме каталогов classpath, в jar кладутся корневые runtime-ресурсы
(ROOT_FILES, напр. logo.png): IDE читает их как "./<файл>" относительно
рабочего каталога, поэтому после распаковки они оказываются в корне.

Использование:
    python bin/make_binary_jar.py            # полный jar (по умолчанию)
    python bin/make_binary_jar.py --safe     # только чистые Java-библиотеки
    python bin/make_binary_jar.py --out build/spark-studio.jar
"""

import argparse
import os
import sys
import zipfile
from datetime import datetime
from pathlib import Path

BIN_DIR = Path(__file__).resolve().parent
PROJECT_ROOT = BIN_DIR.parent
DEFAULT_OUTPUT = BIN_DIR / "binary.jar"

MAIN_CLASS = "org.develnext.jphp.ext.javafx.FXLauncher"

# Полный classpath IDE (источник истины — как в bin/windows.bat и makeexe.py).
# Структура каталогов сохраняется при упаковке (проектный layout), поэтому
# распакованный jar целиком повторяет рабочий корень приложения.
ALL_DIRS = [
    "runtime/jphp-core",
    "runtime/jphp-runtime",
    "gui/jphp-gui-ext",
    "gui/jphp-gui-richtext-ext",
    "gui/jphp-desktop-ext",
    "gui/jphp-systemtray-ext",
    "gui/spark-designer",
    "gui/reactfx-M5",
    "gui/richtextfx",
    "gui/undofx",
    "gui/wellbehavedfx",
    "parser/antlr4-runtime",
    "parser/spark-lexer",
    "parser/jphp-parser",
    "extensions/jphp-json-ext",
    "extensions/jphp-xml-ext",
    "extensions/jphp-zend-ext",
    "extensions/jphp-zip-ext",
    "utils/asm-all",
    "utils/commons-codec",
    "utils/gson",
    "utils/highlights",
    "utils/javassist-GA",
    "utils/slf4j-api",
    "utils/zt-zip",
    "framework/SparkStudio",
    "framework/jphp-app-framework",
    "database/HikariCP-java6",
    "database/jphp-sql-ext",
    "debug/jphp-debugger",
    "network/flowless",
    "network/spark-httpclient-bundle",
    "network/update-service",
    "languages/en",
    "languages/ru",
    "ide/spark-doc",
    "ide/spark-js-platform",
    "ide/spark-java-platform",
    "ide/spark-store",
    "php-sdk/dn-php-sdk",
    "php-sdk/dn-zend-php-sdk",
]

# Чистые Java-библиотеки: JVM читает их из classpath, IDE не читает их с диска
# и не пишет в них — их можно оставить и в маленьком "--safe" варианте.
SAFE_DIRS = [
    "gui/reactfx-M5",
    "gui/richtextfx",
    "gui/undofx",
    "gui/wellbehavedfx",
    "parser/antlr4-runtime",
    "utils/asm-all",
    "utils/commons-codec",
    "utils/gson",
    "utils/javassist-GA",
    "utils/slf4j-api",
    "utils/zt-zip",
    "database/HikariCP-java6",
    "debug/jphp-debugger",
    "network/flowless",
]

# Корневые runtime-ресурсы (относительно корня проекта). IDE читает их как
# "./<файл>", поэтому они должны оказаться в распакованном рабочем корне.
# См. IdeSystem::getOwnFile() / Ide::getOwnFile('logo.png') и т.п.
ROOT_FILES = [
    "logo.png",
]


def log(msg: str) -> None:
    print(f"[{datetime.now():%Y-%m-%d %H:%M:%S}] {msg}")


def error(msg: str) -> None:
    print(f"[ERROR] {msg}", file=sys.stderr)
    sys.exit(1)


def build(output: Path, dirs: list, root_files: list = None) -> None:
    """Собирает jar средствами zipfile (ZIP_STORED)."""
    if root_files is None:
        root_files = ROOT_FILES

    output.parent.mkdir(parents=True, exist_ok=True)
    tmp_out = output.with_suffix(output.suffix + ".tmp")

    manifest = ("Manifest-Version: 1.0\n"
                f"Main-Class: {MAIN_CLASS}\n")

    packed, missing = [], []
    with zipfile.ZipFile(str(tmp_out), "w", zipfile.ZIP_STORED) as zf:
        zf.writestr("META-INF/MANIFEST.MF", manifest)

        for rel in dirs:
            src = PROJECT_ROOT / rel
            if not src.is_dir():
                missing.append(rel)
                continue

            rel_posix = rel.replace(os.sep, "/")
            count = 0
            for base, dirnames, filenames in os.walk(str(src)):
                for name in filenames:
                    full = Path(base) / name
                    sub = full.relative_to(src).as_posix()
                    zf.write(str(full), f"{rel_posix}/{sub}")
                    count += 1
            packed.append((rel, count))
            log(f"  {rel}: {count} файл(ов)")

        for rel in root_files:
            src = PROJECT_ROOT / rel
            if not src.is_file():
                missing.append(rel)
                continue
            zf.write(str(src), rel.replace(os.sep, "/"))
            packed.append((rel, 1))
            log(f"  {rel}: 1 файл (корень)")

    if output.exists():
        output.unlink()
    tmp_out.replace(output)

    total = sum(c for _, c in packed)
    size_mb = output.stat().st_size / (1024 * 1024)
    log(f"готово: {output} ({size_mb:.1f} MB, {total} файл(ов), Main-Class: {MAIN_CLASS})")

    if missing:
        log("пропущены отсутствующие: " + ", ".join(missing))


def main() -> None:
    parser = argparse.ArgumentParser(description="Собрать bin/binary.jar для Spark Studio")
    parser.add_argument("--out", type=Path, default=DEFAULT_OUTPUT, help="куда положить jar")
    parser.add_argument("--safe", action="store_true",
                        help="упаковать только чистые Java-библиотеки (для diagnostic)")
    args = parser.parse_args()

    print("=" * 60)
    print("Spark Studio — binary.jar builder")
    print(f"Project: {PROJECT_ROOT}")
    print("=" * 60)

    dirs = SAFE_DIRS if args.safe else ALL_DIRS
    build(args.out, dirs)


if __name__ == "__main__":
    main()