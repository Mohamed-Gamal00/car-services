# Create Database Using phpMyAdmin

## Quick Steps

### 1. Open phpMyAdmin
- Go to: `http://localhost/phpmyadmin`
- Or: `http://127.0.0.1/phpmyadmin`

### 2. Create Database
1. Click on **"Databases"** tab at the top
2. In the "Create database" section:
   - **Database name**: `quick_clean`
   - **Collation**: Select `utf8mb4_unicode_ci`
3. Click **"Create"** button

### 3. Verify Database Created
- You should see `quick_clean` in the left sidebar
- Click on it to select it

### 4. Run Migrations
Now go back to your terminal and run:
```bash
php artisan migrate:fresh --seed
```

---

## Alternative: Using SQL Tab

If you prefer using SQL:

1. Open phpMyAdmin
2. Click on **"SQL"** tab at the top
3. Paste this SQL:
   ```sql
   CREATE DATABASE IF NOT EXISTS quick_clean 
   CHARACTER SET utf8mb4 
   COLLATE utf8mb4_unicode_ci;
   ```
4. Click **"Go"** button
5. You should see: "Database quick_clean created successfully"

---

## After Database is Created

Run these commands in order:

```bash
# 1. Clear all caches
php artisan optimize:clear

# 2. Run migrations and seeders
php artisan migrate:fresh --seed
```

Expected output:
```
✅ Dropping all tables (if any)
✅ Creating 18 tables
✅ Running seeders
   - AdminSeeder
   - OrderStatusSeeder
   - ServiceSeeder
   - PackageSeeder
   - CarSeeder
   - SettingsSeeder
```

---

## Verify Installation

After successful migration, verify in phpMyAdmin:

1. Click on `quick_clean` database in left sidebar
2. You should see **18 tables**:
   - admins
   - captains
   - cars
   - device_tokens
   - discount_code_services
   - discount_codes
   - failed_jobs
   - jobs
   - notifications
   - order_images
   - order_statuses
   - orders
   - package_features
   - packages
   - password_reset_tokens
   - payments
   - personal_access_tokens
   - ratings
   - services
   - settings
   - user_addresses
   - user_cars
   - user_discount_codes
   - user_packages
   - users

3. Click on `admins` table → Browse
   - You should see 1 admin: admin@quickclean.com

4. Click on `services` table → Browse
   - You should see 4 services

5. Click on `packages` table → Browse
   - You should see 3 packages

---

## Troubleshooting

### Error: "Database already exists"
- That's fine! Just run the migrations:
  ```bash
  php artisan migrate:fresh --seed
  ```

### Error: "Access denied"
- Make sure your `.env` file has:
  ```env
  DB_USERNAME=root
  DB_PASSWORD=
  ```
  (Empty password for XAMPP/WAMP default)

### Error: "Unknown database"
- The database wasn't created
- Go back to phpMyAdmin and create it manually

### Still having issues?
1. Check if Apache and MySQL are running in XAMPP/WAMP
2. Try accessing phpMyAdmin
3. Check `.env` file has correct settings
4. Run: `php artisan config:clear`

---

## Quick Commands

```bash
# Clear caches
php artisan optimize:clear

# Run migrations
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status

# Open Laravel tinker
php artisan tinker
```

---

## Default Login Credentials

After successful migration:

**Admin Panel:**
- Email: `admin@quickclean.com`
- Password: `password`

⚠️ **Change these credentials immediately in production!**

---

## Next Steps

Once database is set up:
1. ✅ Database created
2. ✅ Migrations run
3. ✅ Seeders run
4. ✅ Data populated
5. 🚀 Start building API controllers
6. 🚀 Build frontend

See **NEXT_STEPS.md** for detailed development roadmap.
