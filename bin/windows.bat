@echo off
setlocal enabledelayedexpansion

rem ---- resolve SCRIPT_DIR and APP_HOME ----
set "SCRIPT_DIR=%~dp0"
for %%I in ("%SCRIPT_DIR%..") do set "APP_HOME=%%~fI"

rem ---- build classpath ----
set "CP=%APP_HOME%\runtime\jphp-core"
set "CP=%CP%;%APP_HOME%\runtime\jphp-runtime"
set "CP=%CP%;%APP_HOME%\gui\jphp-gui-ext"
set "CP=%CP%;%APP_HOME%\gui\jphp-gui-richtext-ext"
set "CP=%CP%;%APP_HOME%\gui\jphp-desktop-ext"
set "CP=%CP%;%APP_HOME%\gui\jphp-systemtray-ext"
set "CP=%CP%;%APP_HOME%\gui\spark-designer"
set "CP=%CP%;%APP_HOME%\gui\reactfx-M5"
set "CP=%CP%;%APP_HOME%\gui\richtextfx"
set "CP=%CP%;%APP_HOME%\gui\undofx"
set "CP=%CP%;%APP_HOME%\gui\wellbehavedfx"
set "CP=%CP%;%APP_HOME%\parser\antlr4-runtime"
set "CP=%CP%;%APP_HOME%\parser\spark-lexer"
set "CP=%CP%;%APP_HOME%\parser\jphp-parser"
set "CP=%CP%;%APP_HOME%\extensions\jphp-json-ext"
set "CP=%CP%;%APP_HOME%\extensions\jphp-xml-ext"
set "CP=%CP%;%APP_HOME%\extensions\jphp-zend-ext"
set "CP=%CP%;%APP_HOME%\extensions\jphp-zip-ext"
set "CP=%CP%;%APP_HOME%\database\HikariCP-java6"
set "CP=%CP%;%APP_HOME%\database\jphp-sql-ext"
set "CP=%CP%;%APP_HOME%\debug\jphp-debugger"
set "CP=%CP%;%APP_HOME%\network\flowless"
set "CP=%CP%;%APP_HOME%\utils\asm-all"
set "CP=%CP%;%APP_HOME%\utils\commons-codec"
set "CP=%CP%;%APP_HOME%\utils\gson"
set "CP=%CP%;%APP_HOME%\utils\highlights"
set "CP=%CP%;%APP_HOME%\utils\javassist-GA"
set "CP=%CP%;%APP_HOME%\utils\slf4j-api"
set "CP=%CP%;%APP_HOME%\utils\zt-zip"
set "CP=%CP%;%APP_HOME%\framework\SparkStudio"
set "CP=%CP%;%APP_HOME%\framework\jphp-app-framework"

rem ---- java options ----
set "JAVA_OPTS=-Xms256M -XX:ReservedCodeCacheSize=150m"
set "JAVA_OPTS=%JAVA_OPTS% -Dsun.io.useCanonCaches=false"
set "JAVA_OPTS=%JAVA_OPTS% -Djava.net.preferIPv4Stack=true"
set "JAVA_OPTS=%JAVA_OPTS% -Dfile.encoding=UTF-8"
set "JAVA_OPTS=%JAVA_OPTS% -Dspark.launcher=root"
set "JAVA_OPTS=%JAVA_OPTS% -Dspark.path=%APP_HOME%"
set "JAVA_OPTS=%JAVA_OPTS% -Dglass.disableGrab=true"

rem ---- java home ----
set "JAVA_HOME=%APP_HOME%\bin\jre"

rem ---- logs dir ----
set "LOG_DIR=%APP_HOME%\bin\logs"
if not exist "%LOG_DIR%" mkdir "%LOG_DIR%"

rem ---- clean cache ----
if exist "%USERPROFILE%\.Spark\cache\bytecode_v1" rd /s /q "%USERPROFILE%\.Spark\cache\bytecode_v1"
if exist "%APP_HOME%\bin\cache\bytecode_v1" rd /s /q "%APP_HOME%\bin\cache\bytecode_v1"

rem ---- Linux-specific GDK_BACKEND / LD_PRELOAD / x11-nograb skipped ----

rem ---- set cwd to APP_HOME (app uses relative ./ paths) ----
cd /D "%APP_HOME%"

rem ---- launch ----
"%JAVA_HOME%\bin\java" %JAVA_OPTS% -cp "%CP%" org.develnext.jphp.ext.javafx.FXLauncher %* >"%LOG_DIR%\output.log" 2>"%LOG_DIR%\error.log"

endlocal
