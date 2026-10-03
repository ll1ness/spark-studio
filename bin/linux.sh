#!/bin/bash
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_HOME="$(dirname "$SCRIPT_DIR")"

JAVA_OPTS="-Xms256M -XX:ReservedCodeCacheSize=150m"
JAVA_OPTS="$JAVA_OPTS -Dsun.io.useCanonCaches=false"
JAVA_OPTS="$JAVA_OPTS -Djava.net.preferIPv4Stack=true"
JAVA_OPTS="$JAVA_OPTS -Dfile.encoding=UTF-8"
JAVA_OPTS="$JAVA_OPTS -Dspark.launcher=root"
JAVA_OPTS="$JAVA_OPTS -Dspark.path=$APP_HOME"
JAVA_OPTS="$JAVA_OPTS -Dglass.disableGrab=true"

JAVA_HOME="$APP_HOME/bin/jre"
LOG_DIR="$APP_HOME/bin/logs"
mkdir -p "$LOG_DIR"

rm -rf "$HOME/.Spark/cache/bytecode_v1" "$APP_HOME/bin/cache/bytecode_v1"

GDK_BACKEND="${GDK_BACKEND:-x11}"
export GDK_BACKEND

# Minimal LD_PRELOAD: only block modal hints (prevents XWayland cursor
# constraint). Transient_for passes through so centering works.
NGRAB_SO="$APP_HOME/bin/x11-nograb.so"
if [ ! -f "$NGRAB_SO" ] && command -v gcc >/dev/null 2>&1; then
    gcc -shared -fPIC -o "$NGRAB_SO" "$APP_HOME/bin/x11-nograb.c" -ldl 2>/dev/null
fi
if [ -f "$NGRAB_SO" ]; then
    export LD_PRELOAD="$NGRAB_SO:$LD_PRELOAD"
fi

# Check for pre-built JAR
if [ -f "$APP_HOME/build/spark-studio.jar" ]; then
    # Launch from JAR
    exec "$JAVA_HOME/bin/java" $JAVA_OPTS -jar "$APP_HOME/build/spark-studio.jar" "$@" >"$LOG_DIR/output.log" 2>"$LOG_DIR/error.log"
else
    # Build classpath from directories (fallback)
    CP="$APP_HOME/runtime/jphp-core"
    CP="$CP:$APP_HOME/runtime/jphp-runtime"
    CP="$CP:$APP_HOME/gui/jphp-gui-ext"
    CP="$CP:$APP_HOME/gui/jphp-gui-richtext-ext"
    CP="$CP:$APP_HOME/gui/jphp-desktop-ext"
    CP="$CP:$APP_HOME/gui/jphp-systemtray-ext"
    CP="$CP:$APP_HOME/gui/spark-designer"
    CP="$CP:$APP_HOME/gui/reactfx-M5"
    CP="$CP:$APP_HOME/gui/richtextfx"
    CP="$CP:$APP_HOME/gui/undofx"
    CP="$CP:$APP_HOME/gui/wellbehavedfx"
    CP="$CP:$APP_HOME/parser/antlr4-runtime"
    CP="$CP:$APP_HOME/parser/spark-lexer"
    CP="$CP:$APP_HOME/parser/jphp-parser"
    CP="$CP:$APP_HOME/extensions/jphp-json-ext"
    CP="$CP:$APP_HOME/extensions/jphp-xml-ext"
    CP="$CP:$APP_HOME/extensions/jphp-zend-ext"
    CP="$CP:$APP_HOME/extensions/jphp-zip-ext"
    CP="$CP:$APP_HOME/database/HikariCP-java6"
    CP="$CP:$APP_HOME/database/jphp-sql-ext"
    CP="$CP:$APP_HOME/debug/jphp-debugger"
    CP="$CP:$APP_HOME/network/flowless"
    CP="$CP:$APP_HOME/utils/asm-all"
    CP="$CP:$APP_HOME/utils/commons-codec"
    CP="$CP:$APP_HOME/utils/gson"
    CP="$CP:$APP_HOME/utils/highlights"
    CP="$CP:$APP_HOME/utils/javassist-GA"
    CP="$CP:$APP_HOME/utils/slf4j-api"
    CP="$CP:$APP_HOME/utils/zt-zip"
    CP="$CP:$APP_HOME/framework/SparkStudio"
    CP="$CP:$APP_HOME/framework/jphp-app-framework"

    # Launch from classpath
    exec "$JAVA_HOME/bin/java" $JAVA_OPTS -cp "$CP" org.develnext.jphp.ext.javafx.FXLauncher "$@" >"$LOG_DIR/output.log" 2>"$LOG_DIR/error.log"
fi
