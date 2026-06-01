@echo off
setlocal enabledelayedexpansion

rem ---- resolve APP_HOME (project root) ----
set "SCRIPT_DIR=%~dp0"
for %%I in ("%SCRIPT_DIR%..") do set "APP_HOME=%%~fI"

rem ---- java options ----
set "JAVA_OPTS=-Xms256M -XX:ReservedCodeCacheSize=150m"
set "JAVA_OPTS=%JAVA_OPTS% -Dsun.io.useCanonCaches=false"
set "JAVA_OPTS=%JAVA_OPTS% -Djava.net.preferIPv4Stack=true"
set "JAVA_OPTS=%JAVA_OPTS% -Dfile.encoding=UTF-8"
set "JAVA_OPTS=%JAVA_OPTS% -Dspark.launcher=root"
set "JAVA_OPTS=%JAVA_OPTS% -Dspark.path=%APP_HOME%"
set "JAVA_OPTS=%JAVA_OPTS% -Dglass.disableGrab=true"

rem ---- bundled JRE ----
set "JAVA_HOME=%APP_HOME%\bin\jre"

rem ---- create log dir ----
set "LOG_DIR=%APP_HOME%\bin\logs"
if not exist "%LOG_DIR%" mkdir "%LOG_DIR%"

rem ---- clean JPHP bytecode cache ----
if exist "%USERPROFILE%\.Spark\cache\bytecode_v1" rd /s /q "%USERPROFILE%\.Spark\cache\bytecode_v1"
if exist "%APP_HOME%\bin\cache\bytecode_v1" rd /s /q "%APP_HOME%\bin\cache\bytecode_v1"

rem ---- cd to APP_HOME ----
cd /D "%APP_HOME%"

rem ---- launch JAR ----
"%JAVA_HOME%\bin\java" %JAVA_OPTS% -jar "%APP_HOME%\build\spark-studio.jar" %* >"%LOG_DIR%\output.log" 2>"%LOG_DIR%\error.log"

endlocal