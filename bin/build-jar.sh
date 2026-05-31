#!/bin/bash
# Spark Studio - JAR Builder (Linux/macOS)
# Creates spark-studio.jar in build/

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_HOME="$(dirname "$SCRIPT_DIR")"

BUILD_DIR="$APP_HOME/build"
STAGE_DIR="$BUILD_DIR/__stage__"
JAR_FILE="$BUILD_DIR/spark-studio.jar"

echo "============================================"
echo " Spark Studio - JAR Builder"
echo "============================================"
echo ""
echo "APP_HOME: $APP_HOME"
echo ""

# Clean previous build
rm -rf "$STAGE_DIR" "$JAR_FILE"
mkdir -p "$BUILD_DIR"

echo "[1/5] Copying all module files into staging..."
echo ""

# All source directories from the classpath (see bin/linux.sh)
MODULES=(
    "$APP_HOME/runtime/jphp-core"
    "$APP_HOME/runtime/jphp-runtime"
    "$APP_HOME/gui/jphp-gui-ext"
    "$APP_HOME/gui/jphp-gui-richtext-ext"
    "$APP_HOME/gui/jphp-desktop-ext"
    "$APP_HOME/gui/jphp-systemtray-ext"
    "$APP_HOME/gui/spark-designer"
    "$APP_HOME/gui/reactfx-M5"
    "$APP_HOME/gui/richtextfx"
    "$APP_HOME/gui/undofx"
    "$APP_HOME/gui/wellbehavedfx"
    "$APP_HOME/parser/antlr4-runtime"
    "$APP_HOME/parser/spark-lexer"
    "$APP_HOME/parser/jphp-parser"
    "$APP_HOME/extensions/jphp-json-ext"
    "$APP_HOME/extensions/jphp-xml-ext"
    "$APP_HOME/extensions/jphp-zend-ext"
    "$APP_HOME/extensions/jphp-zip-ext"
    "$APP_HOME/database/HikariCP-java6"
    "$APP_HOME/database/jphp-sql-ext"
    "$APP_HOME/debug/jphp-debugger"
    "$APP_HOME/network/flowless"
    "$APP_HOME/utils/asm-all"
    "$APP_HOME/utils/commons-codec"
    "$APP_HOME/utils/gson"
    "$APP_HOME/utils/highlights"
    "$APP_HOME/utils/javassist-GA"
    "$APP_HOME/utils/slf4j-api"
    "$APP_HOME/utils/zt-zip"
    "$APP_HOME/framework/SparkStudio"
    "$APP_HOME/framework/jphp-app-framework"
    # Additional directories
    "$APP_HOME/php-sdk/dn-php-sdk"
    "$APP_HOME/php-sdk/dn-zend-php-sdk"
    "$APP_HOME/ide/spark-doc"
    "$APP_HOME/ide/spark-java-platform"
    "$APP_HOME/ide/spark-js-platform"
    "$APP_HOME/ide/spark-store"
    "$APP_HOME/languages"
    "$APP_HOME/network/spark-httpclient-bundle"
    "$APP_HOME/network/update-service"
)

for mod in "${MODULES[@]}"; do
    if [ -d "$mod" ]; then
        echo "    [COPY] $mod"
        cp -r "$mod/"* "$STAGE_DIR/" 2>/dev/null || true
    else
        echo "    [SKIP] $mod (not found)"
    fi
done

echo ""
echo "[2/5] Merging META-INF/services/php.runtime.ext.support.Extension files..."
echo ""

EXT_LIST=$(mktemp)
# Find and merge all Extension service files
find "$STAGE_DIR" -path "*/META-INF/services/php.runtime.ext.support.Extension" | while read -r f; do
    echo "    Found: $f"
    cat "$f" >> "$EXT_LIST"
    echo "" >> "$EXT_LIST"
done

# Remove the individual service files
find "$STAGE_DIR" -path "*/META-INF/services/php.runtime.ext.support.Extension" -delete 2>/dev/null || true

# Create merged service file
mkdir -p "$STAGE_DIR/META-INF/services"
cp "$EXT_LIST" "$STAGE_DIR/META-INF/services/php.runtime.ext.support.Extension"
rm -f "$EXT_LIST"

echo ""
echo "[3/5] Creating JAR manifest..."
echo ""

mkdir -p "$STAGE_DIR/META-INF"
cat > "$STAGE_DIR/META-INF/MANIFEST.MF" << 'EOF'
Manifest-Version: 1.0
Main-Class: org.develnext.jphp.ext.javafx.FXLauncher

EOF

echo ""
echo "[4/5] Creating JAR file..."
echo ""

cd "$STAGE_DIR"

if command -v jar &>/dev/null; then
    echo "    Using: jar"
    jar cf "$JAR_FILE" .
elif command -v zip &>/dev/null; then
    echo "    Using: zip (as JAR)"
    zip -r -q "$JAR_FILE" .
    # JAR is just a ZIP with META-INF/MANIFEST.MF
else
    echo "    ERROR: neither jar nor zip command found!"
    exit 1
fi

cd "$APP_HOME"

echo ""
echo "[5/5] Cleaning up..."
echo ""

rm -rf "$STAGE_DIR"

echo "============================================"
echo "  Build complete!"
echo ""
echo "  JAR file: $JAR_FILE"
echo "  Size: $(du -h "$JAR_FILE" | cut -f1)"
echo "============================================"