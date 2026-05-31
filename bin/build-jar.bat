@echo off
setlocal enabledelayedexpansion

echo ============================================
echo  Spark Studio - JAR Builder
echo  Creates spark-studio.jar in build/
echo ============================================
echo.

rem ---- resolve paths ----
set "SCRIPT_DIR=%~dp0"
for %%I in ("%SCRIPT_DIR%..") do set "APP_HOME=%%~fI"

set "BUILD_DIR=%APP_HOME%\build"
set "STAGE_DIR=%BUILD_DIR%\__stage__"
set "JAR_FILE=%BUILD_DIR%\spark-studio.jar"

echo APP_HOME: %APP_HOME%
echo.

rem ---- clean previous build ----
if exist "%STAGE_DIR%" rd /s /q "%STAGE_DIR%"
if exist "%JAR_FILE%" del /q "%JAR_FILE%"
if not exist "%BUILD_DIR%" mkdir "%BUILD_DIR%"
if not exist "%STAGE_DIR%" mkdir "%STAGE_DIR%"

echo [1/5] Copying all module files into staging...
echo.

rem ---- All source directories from the classpath (see bin/windows.bat) ----
set "MODULES[0]=%APP_HOME%\runtime\jphp-core"
set "MODULES[1]=%APP_HOME%\runtime\jphp-runtime"
set "MODULES[2]=%APP_HOME%\gui\jphp-gui-ext"
set "MODULES[3]=%APP_HOME%\gui\jphp-gui-richtext-ext"
set "MODULES[4]=%APP_HOME%\gui\jphp-desktop-ext"
set "MODULES[5]=%APP_HOME%\gui\jphp-systemtray-ext"
set "MODULES[6]=%APP_HOME%\gui\spark-designer"
set "MODULES[7]=%APP_HOME%\gui\reactfx-M5"
set "MODULES[8]=%APP_HOME%\gui\richtextfx"
set "MODULES[9]=%APP_HOME%\gui\undofx"
set "MODULES[10]=%APP_HOME%\gui\wellbehavedfx"
set "MODULES[11]=%APP_HOME%\parser\antlr4-runtime"
set "MODULES[12]=%APP_HOME%\parser\spark-lexer"
set "MODULES[13]=%APP_HOME%\parser\jphp-parser"
set "MODULES[14]=%APP_HOME%\extensions\jphp-json-ext"
set "MODULES[15]=%APP_HOME%\extensions\jphp-xml-ext"
set "MODULES[16]=%APP_HOME%\extensions\jphp-zend-ext"
set "MODULES[17]=%APP_HOME%\extensions\jphp-zip-ext"
set "MODULES[18]=%APP_HOME%\database\HikariCP-java6"
set "MODULES[19]=%APP_HOME%\database\jphp-sql-ext"
set "MODULES[20]=%APP_HOME%\debug\jphp-debugger"
set "MODULES[21]=%APP_HOME%\network\flowless"
set "MODULES[22]=%APP_HOME%\utils\asm-all"
set "MODULES[23]=%APP_HOME%\utils\commons-codec"
set "MODULES[24]=%APP_HOME%\utils\gson"
set "MODULES[25]=%APP_HOME%\utils\highlights"
set "MODULES[26]=%APP_HOME%\utils\javassist-GA"
set "MODULES[27]=%APP_HOME%\utils\slf4j-api"
set "MODULES[28]=%APP_HOME%\utils\zt-zip"
set "MODULES[29]=%APP_HOME%\framework\SparkStudio"
set "MODULES[30]=%APP_HOME%\framework\jphp-app-framework"

rem ---- Additional directories not in the main classpath but needed ----
set "MODULES[31]=%APP_HOME%\php-sdk\dn-php-sdk"
set "MODULES[32]=%APP_HOME%\php-sdk\dn-zend-php-sdk"
set "MODULES[33]=%APP_HOME%\ide\spark-doc"
set "MODULES[34]=%APP_HOME%\ide\spark-java-platform"
set "MODULES[35]=%APP_HOME%\ide\spark-js-platform"
set "MODULES[36]=%APP_HOME%\ide\spark-store"
set "MODULES[37]=%APP_HOME%\languages"
set "MODULES[38]=%APP_HOME%\network\spark-httpclient-bundle"
set "MODULES[39]=%APP_HOME%\network\update-service"

rem ---- Build module list as a PowerShell array and copy all at once ----
set "PS_MODULES="
for /l %%i in (0,1,39) do (
    if defined PS_MODULES (
        set "PS_MODULES=!PS_MODULES!,'!MODULES[%%i]:\=\\!'"
    ) else (
        set "PS_MODULES='!MODULES[%%i]:\=\\!'"
    )
)

powershell -NoProfile -Command ^
    "$stage='%STAGE_DIR:\=\\%'; " ^
    "$modules=@(!PS_MODULES!); " ^
    "foreach($s in $modules){if(Test-Path $s){Write-Host ('    [COPY] '+$s);Copy-Item -Path ($s+'\*') -Dest $stage -Recurse -Force -ErrorAction SilentlyContinue}else{Write-Host ('    [SKIP] '+$s+' (not found)')}}"
if !ERRORLEVEL! NEQ 0 (
    echo    ERROR: Copy step failed!
    goto :error
)

echo.
echo [2/5] Merging META-INF/services/php.runtime.ext.support.Extension files...
echo.

set "EXT_LIST=%STAGE_DIR%\__ext_list.tmp"
if exist "%EXT_LIST%" del /q "%EXT_LIST%"
type nul > "%EXT_LIST%"

rem ---- Find and merge all Extension service files (only in META-INF/services/) ----
for /r "%STAGE_DIR%" %%F in (php.runtime.ext.support.Extension) do (
    set "FPATH=%%F"
    set "FPATH=!FPATH:%STAGE_DIR%=!"
    echo !FPATH! | findstr /i /c:"META-INF\services" >nul
    if !ERRORLEVEL! EQU 0 (
        echo    Found: %%F
        type "%%F" >> "%EXT_LIST%"
        echo. >> "%EXT_LIST%"
    )
)

rem ---- Remove the individual service files ----
for /r "%STAGE_DIR%" %%F in (php.runtime.ext.support.Extension) do (
    set "FPATH=%%F"
    set "FPATH=!FPATH:%STAGE_DIR%=!"
    echo !FPATH! | findstr /i /c:"META-INF\services" >nul
    if !ERRORLEVEL! EQU 0 (
        del /q "%%F" 2>nul
    )
)

rem ---- Create merged service file ----
if not exist "%STAGE_DIR%\META-INF\services" mkdir "%STAGE_DIR%\META-INF\services"
copy /y "%EXT_LIST%" "%STAGE_DIR%\META-INF\services\php.runtime.ext.support.Extension" >nul
del /q "%EXT_LIST%"

echo.
echo [3/5] Creating JAR manifest...
echo.

rem ---- Create manifest with Main-Class ----
if not exist "%STAGE_DIR%\META-INF" mkdir "%STAGE_DIR%\META-INF"
(
    echo Manifest-Version: 1.0
    echo Main-Class: org.develnext.jphp.ext.javafx.FXLauncher
    echo.
) > "%STAGE_DIR%\META-INF\MANIFEST.MF"

echo.
echo [4/5] Creating JAR file...
echo.

rem ---- Use PowerShell to create JAR (ZIP with .jar extension) ----
powershell -NoProfile -Command ^
    "$src='%STAGE_DIR:\=\\%'; " ^
    "$dst='%JAR_FILE:\=\\%'; " ^
    "if(Test-Path $dst){Remove-Item $dst}; " ^
    "Add-Type -AssemblyName System.IO.Compression.FileSystem; " ^
    "[System.IO.Compression.ZipFile]::CreateFromDirectory($src,$dst,[System.IO.Compression.CompressionLevel]::Optimal,$false); " ^
    "$zip=[System.IO.Compression.ZipFile]::OpenRead($dst); " ^
    "Write-Host ('JAR created with '+$zip.Entries.Count+' entries'); " ^
    "$zip.Dispose()"
if !ERRORLEVEL! NEQ 0 (
    echo    ERROR: PowerShell JAR creation failed!
    goto :error
)

echo.
echo [5/5] Cleaning up...
echo.

if exist "%STAGE_DIR%" rd /s /q "%STAGE_DIR%"

echo ============================================
echo  Build complete!
echo.
echo  JAR file: %JAR_FILE%
echo.
for %%I in ("%JAR_FILE%") do echo  Size: %%~zI bytes
echo ============================================
goto :eof

:error
cd /d "%APP_HOME%"
echo.
echo ============================================
echo  BUILD FAILED!
echo ============================================
exit /b 1

endlocal