@echo off
echo ============================================
echo   Quick Clean - Database Setup
echo ============================================
echo.

echo Step 1: Clearing Laravel caches...
php artisan optimize:clear
echo.

echo Step 2: Please create the database manually:
echo.
echo   1. Open phpMyAdmin: http://localhost/phpmyadmin
echo   2. Click "Databases" tab
echo   3. Create database: quick_clean
echo   4. Collation: utf8mb4_unicode_ci
echo   5. Click "Create"
echo.

pause

echo.
echo Step 3: Running migrations and seeders...
php artisan migrate:fresh --seed

echo.
echo ============================================
echo   Setup Complete!
echo ============================================
echo.
echo Default Admin Login:
echo   Email: admin@quickclean.com
echo   Password: password
echo.
echo Next: Read NEXT_STEPS.md for development guide
echo.

pause
