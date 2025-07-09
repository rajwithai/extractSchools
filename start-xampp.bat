@echo off
echo Starting XAMPP Dominican Schools Application...
echo.
echo 1. Opening XAMPP Control Panel...
start "XAMPP Control" "C:\xampp\xampp-control.exe"
echo.
echo 2. Waiting for you to start Apache in XAMPP Control Panel...
echo    (Click "Start" next to Apache in the XAMPP window)
echo.
pause
echo.
echo 3. Opening Dominican Schools Application...
start "Dominican Schools" "http://localhost/dominican-schools/working.php"
echo.
echo ✅ Application should now be running in your browser!
echo.
echo Available URLs:
echo   - Simple Test: http://localhost/dominican-schools/working.php
echo   - Full App: http://localhost/dominican-schools/schools.php
echo   - Status Page: http://localhost/dominican-schools/schools.php?page=status
echo   - Fetch Schools: http://localhost/dominican-schools/schools.php?page=fetch
echo   - If you need the full Laravel version (requires PHP 8.2+):
echo     http://localhost/dominican-schools/public/
echo.
pause 