# Quick Clean - Database Migration Guide

## Overview
This guide explains the new, clean database structure for the Quick Clean booking system. The old ecommerce-based structure has been replaced with a professional booking system architecture.

## What Changed

### Removed Tables (No Longer Needed)
- All ecommerce-related tables (carts, wishlist, product_images, etc.)
- Category tables (main_categories, first_sub_categories, sec_sub_categories)
- Shipping tables (shipping_companies, shipping_locations, shipping_types)
- Guest tables (guests, wishlist_products_guest)
- Product-related tables (colors, product_features, product_availabilities)
- Bulk orders, representatives orders
- Currency tables
- Countries and cities (can be added back if needed for location services)
- Advertisement and banner tables
- Store features
- Comments
- Return products
- Cookie discount IDs

### New Clean Structure

#### Core Tables

**1. users**
- Simplified user structure
- Fields: name, email, phone, password, avatar, status, preferred_language
- Removed: sec_name, family_name, default_currency_id, verification fields

**2. admins**
- Clean admin structure
- Fields: name, email, password, avatar, is_super_admin, status

**3. captains**
- Service providers
- Fields: name, phone, password, avatar, status (available/busy), is_active, location, preferred_language

**4. services** (replaces products table)
- Car cleaning services
- Fields: name, name_en, description, price, duration, image, icon, is_active, sort_order

**5. packages**
- Prepaid service bundles
- Fields: name, name_en, description, wash_count, price, duration, validity_days, image, icon

**6. user_packages**
- User subscriptions to packages
- Fields: user_id, package_id, reference, remaining_washes, start_date, expiry_date, status, notified_expired

**7. orders**
- Booking orders
- Fields: number, user_id, service_id, user_package_id, captain_id, car_id, order_status_id
- Booking details: booking_date, booking_time, address, location coordinates
- Pricing: service_price, discount_amount, total_price
- Payment: payment_method, payment_status
- Status: is_arrived, is_completed, rating_skipped

**8. cars**
- Car brands and models
- Fields: brand, model, year, color, is_active

**9. user_cars** (pivot table)
- User's registered cars
- Fields: user_id, car_id, car_model, car_number, is_default

**10. user_addresses**
- Saved user addresses
- Fields: user_id, title, address, latitude, longitude, city, district, is_default

**11. order_statuses**
- Order workflow states
- Fields: name, name_en, color, sort_order, is_active

**12. ratings**
- Captain and service ratings
- Fields: order_id, user_id, captain_id, stars, comment

**13. discount_codes**
- Promotional discount codes
- Fields: code, name, type (percentage/fixed), value, usage limits, dates, status

**14. payments**
- Payment records (polymorphic)
- Fields: user_id, payable_type, payable_id, payment_id, reference, amount, method, status

**15. device_tokens** (polymorphic)
- Push notification tokens for users and captains
- Fields: tokenable_type, tokenable_id, token, device_type

**16. settings**
- Application settings (key-value store)
- Fields: key, value, type, group, description

**17. notifications**
- Laravel notifications table
- Standard Laravel structure

**18. System Tables**
- password_reset_tokens
- personal_access_tokens
- failed_jobs
- jobs

## Migration Steps

### Step 1: Backup Current Database
```bash
# Backup your current database before proceeding
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

### Step 2: Update .env File
Ensure your database credentials are correct:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 3: Run Fresh Migration
```bash
# This will drop all tables and recreate them
php artisan migrate:fresh --seed
```

### Step 4: Verify Seeded Data
The seeders will create:
- 1 Super Admin (email: admin@quickclean.com, password: password)
- 8 Order Statuses (Pending, Confirmed, Assigned, On the way, Arrived, In progress, Completed, Cancelled)
- 4 Services (Exterior Wash, Interior Cleaning, Full Service, Wax Service)
- 3 Packages (Basic, Premium, VIP)
- 31 Car brands/models
- Application settings

### Step 5: Update Models
All models have been updated to match the new structure. Key changes:
- `Product` → `Service`
- Removed ecommerce relationships
- Added booking-specific methods
- Improved accessors and scopes

## Data Migration (If Needed)

If you need to migrate existing data from the old structure:

### Migrate Users
```sql
-- Users are mostly compatible, just need to map fields
INSERT INTO users (name, email, phone, password, created_at, updated_at)
SELECT 
    CONCAT(first_name, ' ', family_name) as name,
    email,
    phone_number as phone,
    password,
    created_at,
    updated_at
FROM old_users;
```

### Migrate Products to Services
```sql
-- Convert products to services
INSERT INTO services (name, name_en, description, description_en, price, duration, image, is_active, created_at, updated_at)
SELECT 
    name,
    name_en,
    description,
    description_en,
    price,
    duration,
    image,
    is_active,
    created_at,
    updated_at
FROM old_products
WHERE duration IS NOT NULL; -- Only products with duration (services)
```

### Migrate Orders
```sql
-- Orders need careful mapping
INSERT INTO orders (
    number, user_id, service_id, user_package_id, captain_id, 
    booking_date, booking_time, address, latitude, longitude,
    total_price, payment_method, payment_status, order_status_id,
    created_at, updated_at
)
SELECT 
    number,
    user_id,
    product_id as service_id,
    user_package_id,
    captain_id,
    booking_date,
    booking_time,
    location as address,
    latitude,
    longitude,
    total_price,
    payment_method,
    payment_status,
    order_status_id,
    created_at,
    updated_at
FROM old_orders;
```

## Updated Commands

### Process Unassigned Orders
```bash
php artisan orders:process-unassigned
```
Finds unassigned orders for today and assigns available captains.

### Notify Expired Packages
```bash
php artisan packages:notify-expired
```
Notifies users about expired or expiring packages.

## Cron Jobs Setup

Add to your `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Process unassigned orders every 5 minutes
    $schedule->command('orders:process-unassigned')
        ->everyFiveMinutes();
    
    // Check for expired packages daily
    $schedule->command('packages:notify-expired')
        ->daily();
}
```

## API Changes

### Endpoints to Update

**Old:** `/api/products` → **New:** `/api/services`
**Old:** `/api/checkout` → **New:** `/api/bookings`

### Request Structure Changes

**Old Checkout:**
```json
{
    "product_id": 1,
    "quantity": 1,
    "cart_items": []
}
```

**New Booking:**
```json
{
    "service_id": 1,
    "user_package_id": null,
    "booking_date": "2024-01-15",
    "booking_time": "14:00",
    "car_id": 1,
    "address": "123 Main St",
    "latitude": 24.7136,
    "longitude": 46.6753
}
```

## Model Relationships

### User
- hasMany: orders, addresses, packages, ratings, payments
- belongsToMany: cars, discountCodes
- morphMany: deviceTokens

### Captain
- hasMany: orders, ratings
- morphMany: deviceTokens

### Order
- belongsTo: user, service, userPackage, captain, car, orderStatus
- hasMany: images
- hasOne: rating
- morphMany: payments

### Service
- hasMany: orders
- belongsToMany: discountCodes

### Package
- hasMany: userPackages
- hasMany: features

### UserPackage
- belongsTo: user, package
- hasMany: orders
- morphMany: payments

## Testing the New Structure

### 1. Create a Test User
```bash
php artisan tinker
```
```php
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '+966500000000',
    'password' => bcrypt('password'),
    'status' => 'active',
]);
```

### 2. Create a Test Captain
```php
$captain = Captain::create([
    'name' => 'Test Captain',
    'phone' => '+966500000001',
    'password' => bcrypt('password'),
    'status' => 'available',
    'is_active' => true,
]);
```

### 3. Create a Test Order
```php
$service = Service::first();
$order = Order::create([
    'user_id' => $user->id,
    'service_id' => $service->id,
    'booking_date' => now()->addDay(),
    'booking_time' => '14:00',
    'address' => 'Test Address',
    'latitude' => 24.7136,
    'longitude' => 46.6753,
    'total_price' => $service->price,
    'payment_status' => 'paid',
    'order_status_id' => 1,
]);
```

## Next Steps

1. ✅ Database structure created
2. ✅ Models updated
3. ✅ Seeders created
4. ✅ Commands updated
5. ⏳ Update controllers to use new structure
6. ⏳ Update API resources
7. ⏳ Update frontend views
8. ⏳ Test booking flow
9. ⏳ Test captain assignment
10. ⏳ Test package system

## Support

For questions or issues with the migration, refer to:
- Models in `app/Models/`
- Migrations in `database/migrations/`
- Seeders in `database/seeders/`
- Commands in `app/Console/Commands/`
