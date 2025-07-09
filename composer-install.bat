@echo off
echo Installing Composer dependencies for Dominican Schools Project...
php composer.phar install --ignore-platform-reqs
echo.
echo Dependencies installed successfully!
echo You can now access: http://localhost/dominican-schools/public/
pause 