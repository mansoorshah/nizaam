@echo off
echo ========================================
echo Nizaam Company OS - Installation Script
echo ========================================
echo.

REM Check if MySQL is running
echo [1/4] Checking MySQL connection...
mysql -u root -e "SELECT 1" >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: MySQL is not running!
    echo Please start MySQL in XAMPP Control Panel and try again.
    pause
    exit /b 1
)
echo MySQL is running ✓

REM Create database
echo.
echo [2/4] Creating database...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS nizaam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
if %errorlevel% equ 0 (
    echo Database created ✓
) else (
    echo WARNING: Database might already exist
)

REM Import schema
echo.
echo [3/4] Importing database schema...
mysql -u root nizaam < database\schema.sql 2>nul
if %errorlevel% equ 0 (
    echo Schema imported ✓
) else (
    echo ERROR: Failed to import schema
    pause
    exit /b 1
)

REM Import seed data
echo.
echo [4/4] Importing seed data...
mysql -u root nizaam < database\seed.sql 2>nul
if %errorlevel% equ 0 (
    echo Seed data imported ✓
) else (
    echo ERROR: Failed to import seed data
    pause
    exit /b 1
)

echo.
echo ========================================
echo Installation Complete! ✓
echo ========================================
echo.
echo Access your application at:
echo http://localhost/nizaam/public
echo.
echo Default login credentials:
echo Email: admin@nizaam.com
echo Password: admin123
echo.
echo IMPORTANT: Change the default password after first login!
echo.
pause
