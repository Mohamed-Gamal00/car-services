# Fix Database Connection Issue

## Problem
Laravel is trying to connect to database `clenweb_new` with user `clenweb_clenweb` but the password is incorrect or missing.

## Solution

### Option 1: Use Existing Database (Recommended)
If you want to use the existing database `clenweb_new`:

1. **Update `.env` file** with the correct password:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=clenweb_new
   DB_USERNAME=clenweb_clenweb
   DB_PASSWORD=YOUR_ACTUAL_PASSWORD_HERE
   ```

2. **Clear all caches**:
   ```bash
   php artisan optimize:clear
   ```

3. **Run migrations**:
   ```bash
   php artisan migrate:fresh --seed
   ```

### Option 2: Create New Database
If you want to start fresh with a new database:

1. **Create new database** in MySQL:
   ```sql
   CREATE DATABASE quick_clean CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Create new user** (optional):
   ```sql
   CREATE USER 'quick_clean_user'@'localhost' IDENTIFIED BY 'your_password';
   GRANT ALL PRIVILEGES ON quick_clean.* TO 'quick_clean_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **Update `.env` file**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=quick_clean
   DB_USERNAME=quick_clean_user
   DB_PASSWORD=your_password
   ```

4. **Clear all caches**:
   ```bash
   php artisan optimize:clear
   ```

5. **Run migrations**:
   ```bash
   php artisan migrate:fresh --seed
   ```

### Option 3: Use Root User (Development Only)
If you're in development and have root access:

1. **Update `.env` file**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=quick_clean
   DB_USERNAME=root
   DB_PASSWORD=your_root_password
   ```

2. **Create database** (if it doesn't exist):
   ```bash
   mysql -u root -p
   ```
   Then in MySQL:
   ```sql
   CREATE DATABASE quick_clean CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   EXIT;
   ```

3. **Clear all caches**:
   ```bash
   php artisan optimize:clear
   ```

4. **Run migrations**:
   ```bash
   php artisan migrate:fresh --seed
   ```

## How to Find Your Database Password

### Method 1: Check cPanel/Hosting Panel
- Log into your hosting control panel
- Go to MySQL Databases section
- Find the user `clenweb_clenweb`
- Reset password if needed

### Method 2: Check Other Config Files
Look for database credentials in:
- Old `.env` file backups
- `config/database.php` (if hardcoded)
- Hosting documentation
- Server configuration files

### Method 3: Reset MySQL Password
If you have root access:

```bash
# Login as root
mysql -u root -p

# Change password for user
ALTER USER 'clenweb_clenweb'@'localhost' IDENTIFIED BY 'new_password';
FLUSH PRIVILEGES;
EXIT;
```

Then update `.env` with the new password.

## After Fixing Connection

Once you have the correct credentials:

1. **Clear all caches**:
   ```bash
   php artisan optimize:clear
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test connection**:
   ```bash
   php artisan migrate:status
   ```

3. **Run fresh migration**:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Verify data**:
   ```bash
   php artisan tinker
   ```
   Then in tinker:
   ```php
   User::count();
   Service::count();
   Package::count();
   OrderStatus::count();
   ```

## Expected Results After Successful Migration

You should see:
- ✅ 18 tables created
- ✅ 1 admin account (admin@quickclean.com)
- ✅ 8 order statuses
- ✅ 4 services
- ✅ 3 packages with features
- ✅ 31 car models
- ✅ 12 settings

## Troubleshooting

### Error: "Access denied"
- Check username and password are correct
- Check user has permissions on the database
- Try connecting with MySQL client directly

### Error: "Unknown database"
- Create the database first
- Check database name spelling

### Error: "Connection refused"
- Check MySQL service is running
- Check host and port are correct

### Still Having Issues?
1. Test MySQL connection directly:
   ```bash
   mysql -h localhost -u clenweb_clenweb -p clenweb_new
   ```

2. Check MySQL error log for details

3. Verify PHP MySQL extension is installed:
   ```bash
   php -m | grep mysql
   ```

## Quick Commands Reference

```bash
# Clear all caches
php artisan optimize:clear

# Fresh migration with seeders
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Run seeders only
php artisan db:seed

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

## Need Help?

If you're still stuck:
1. Check the exact error message
2. Verify MySQL is running
3. Test connection with MySQL client
4. Check user permissions in MySQL
5. Review `.env` file for typos

---

**Note**: The warning about "mysqli already loaded" is harmless and can be ignored. It's a PHP configuration issue that doesn't affect functionality.
