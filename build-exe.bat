@echo off
chcp 65001 >nul
title Spark Studio — Build EXE

python3 bin\build-exe.py %*
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [!] Build failed (error code: %ERRORLEVEL%)
    pause
    exit /b %ERRORLEVEL%
)

echo [✓] SparkStudio.exe built successfully
echo.
pause
