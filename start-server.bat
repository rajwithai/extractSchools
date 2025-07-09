@echo off
echo Starting Dominican Republic Schools Data System...
echo.
echo Installing dependencies...
C:\xampp\php\php.exe composer.phar install --ignore-platform-reqs > nul 2>&1
echo Dependencies ready!
echo.
echo Starting Laravel development server...
echo Your application will be available at: http://127.0.0.1:8000/
echo.
echo Press Ctrl+C to stop the server
echo.
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000 