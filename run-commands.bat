@echo off
echo Dominican Republic Schools Data System - Command Testing
echo ========================================================
echo.
echo Available Commands:
echo.
echo 1. dominicangob:import    - Client Architecture Demo
echo 2. fetch:dominican-schools - Fetch Real School Data
echo.
set /p choice="Enter command number (1 or 2): "

if "%choice%"=="1" (
    echo.
    echo Running: C:\xampp\php\php.exe artisan dominicangob:import
    echo.
    C:\xampp\php\php.exe artisan dominicangob:import
) else if "%choice%"=="2" (
    echo.
    echo Running: C:\xampp\php\php.exe artisan fetch:dominican-schools
    echo.
    C:\xampp\php\php.exe artisan fetch:dominican-schools
) else (
    echo Invalid choice! Please run the script again.
)

echo.
pause 