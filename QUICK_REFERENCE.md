# Quick Clean - Quick Reference Guide

## 🗄️ Database Tables

### Users & Authentication
| Table | Purpose | Key Fields |
|-------|---------|------------|
| `users` | Customers | name, email, phone, status |
| `admins` | Admin users | name, email, is_super_admin |
| `captains` | Service providers | name, phone, status (available/busy) |
| `password_reset_tokens` | Password resets | email, token |
| `personal_access_tokens` | API tokens | tokenable_type, token |

### Services & Packages
| Table | Purpose | Key Fields |
|-------|---------|------------|
| `services` | Car cleaning services | name, price, duration |
| `packages` | Service bundles | name, wash_count, price, validity_days |
| `package_features` | Package features | package_id, feature |
| `user_packages` | User subscriptions | user_id, package_id, remaining_washes, status |

### Bookings
| Table | Purpose | Key Fields |
|-------|---------|------------|
| `orders` | Service bookings | user_id, service_id, captain_id, booking_date |
| `order_statuses` | Order states | name, color, sort_order |
| `order_images` | Order photos | order_id, image_path, type |

### Vehicles & Locations
| Table | Purpose | Key Fields |
|-------|---------|------------|
| `cars` | Car models | brand, model, year |
| `user_cars` | User's cars | user_id, car_id, car_number |
| `user_addresses` | Saved addresses | user_id, address, latitude, longitude |

### Payments & Discounts
| Table | Purpose | Key Fields |
|-------|---------|------------|
| `payments` | Payment records | payable_type, amount, status |
| `discount_codes` | Promo codes | code, type, value, usage_limit |
| `discount_code_services` | Service discounts | discount_code_id, service_id |
| `user_discount_codes` | Usage tracking | user_id, discount_code_id, used_at |

### Ratings & Notifications
| Table | Purpose | Key Fields |
|-------|---------|------------|
| `ratings` | Service ratings | order_id, captain_id, stars |
| `device_tokens` | Push tokens | tokenable_type, token |
| `notifications` | Laravel notifications | notifiable_type, data |
| `settings` | App settings | key, value, type |

### System
| Table | Purpose |
|-------|---------|
| `jobs` | Queue jobs |
| `failed_jobs` | Failed jobs |

---

## 📋 Order Statuses

| ID | Name (AR) | Name (EN) | Color | Description |
|----|-----------|-----------|-------|-------------|
| 1 | قيد الانتظار | Pending | #ffc107 | Order created, awaiting captain |
| 2 | تم التأكيد | Confirmed | #17a2b8 | Order confirmed by admin |
| 3 | تم التعيين | Assigned | #007bff | Captain assigned |
| 4 | في الطريق | On the way | #fd7e14 | Captain heading to location |
| 5 | وصل الكابتن | Captain arrived | #6f42c1 | Captain at location |
| 6 | جاري العمل | In progress | #20c997 | Service in progress |
| 7 | مكتمل | Completed | #28a745 | Service completed |
| 8 | ملغي | Cancelled | #dc3545 | Order cancelled |

---

## 🔑 Key Model Methods

### User
```php
$user->orders()              // User's orders
$user->addresses()           // Saved addresses
$user->cars()                // Registered cars
$user->packages()            // Purchased packages
$user->activePackage()       // Current active package
$user->deviceTokens()        // Push notification tokens
```

### Captain
```php
$captain->orders()           // Captain's orders
$captain->ratings()          // Captain's ratings
$captain->averageRating()    // Average rating
$captain->isAvailable()      // Check if available
$captain->getCurrentOrder()  // Current active order
```

### Order
```php
$order->user()               // Order owner
$order->service()            // Booked service
$order->captain()            // Assigned captain
$order->userPackage()        // Package used (if any)
$order->isFromPackage()      // Check if from package
$order->isPaid()             // Check payment status
$order->getServiceDuration() // Service duration in minutes
```

### Service
```php
$service->orders()           // Service bookings
$service->discountCodes()    // Applicable discounts
$service->getDurationInMinutes() // Duration in minutes
Service::active()            // Active services only
```

### Package
```php
$package->userPackages()     // User subscriptions
$package->features()         // Package features
$package->getDurationInMinutes() // Duration in minutes
$package->getPricePerWash()  // Price per wash
```

### UserPackage
```php
$userPackage->user()         // Package owner
$userPackage->package()      // Package details
$userPackage->orders()       // Orders using this package
$userPackage->isActive()     // Check if active
$userPackage->isExpired()    // Check if expired
$userPackage->getDaysRemaining() // Days until expiry
```

---

## 🎯 Common Queries

### Find Available Captains
```php
Captain::available()->get()
// or
Captain::where('status', 'available')->where('is_active', true)->get()
```

### Today's Unassigned Orders
```php
Order::whereNull('captain_id')
    ->where('payment_status', 'paid')
    ->where('booking_date', now()->toDateString())
    ->get()
```

### User's Active Package
```php
$user->packages()
    ->where('status', 'active')
    ->where('expiry_date', '>=', now())
    ->where('remaining_washes', '>', 0)
    ->first()
```

### Active Services
```php
Service::active()->ordered()->get()
```

### Active Packages
```php
Package::active()->ordered()->with('features')->get()
```

### Captain's Average Rating
```php
$captain->ratings()->avg('stars')
```

### Orders by Status
```php
Order::where('order_status_id', 3)->get() // Assigned
Order::completed()->get()                  // Completed
Order::pending()->get()                    // Pending assignment
```

---

## ⚙️ Artisan Commands

### Database
```bash
php artisan migrate:fresh --seed    # Fresh migration with seeders
php artisan db:seed                 # Run seeders only
php artisan migrate:rollback        # Rollback last migration
```

### Custom Commands
```bash
php artisan orders:process-unassigned  # Process unassigned orders
php artisan packages:notify-expired    # Notify expired packages
```

### Queue
```bash
php artisan queue:work              # Process queue jobs
php artisan queue:listen            # Listen for queue jobs
php artisan queue:failed            # List failed jobs
php artisan queue:retry all         # Retry all failed jobs
```

### Cache
```bash
php artisan cache:clear             # Clear application cache
php artisan config:clear            # Clear config cache
php artisan route:clear             # Clear route cache
php artisan view:clear              # Clear view cache
```

---

## 🔐 Default Credentials

### Super Admin
- **Email**: admin@quickclean.com
- **Password**: password

### Test Captain (Create manually)
```php
Captain::create([
    'name' => 'Test Captain',
    'phone' => '+966500000001',
    'password' => bcrypt('password'),
    'status' => 'available',
    'is_active' => true,
]);
```

### Test User (Create manually)
```php
User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '+966500000000',
    'password' => bcrypt('password'),
    'status' => 'active',
]);
```

---

## 📱 API Endpoints (To Be Implemented)

### Authentication
- `POST /api/auth/register` - Register user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user

### Services
- `GET /api/services` - List services
- `GET /api/services/{id}` - Service details

### Packages
- `GET /api/packages` - List packages
- `GET /api/packages/{id}` - Package details
- `POST /api/packages/{id}/subscribe` - Subscribe to package

### Bookings
- `POST /api/bookings` - Create booking
- `GET /api/bookings` - User's bookings
- `GET /api/bookings/{id}` - Booking details
- `POST /api/bookings/{id}/cancel` - Cancel booking
- `POST /api/bookings/{id}/rate` - Rate service

### Profile
- `GET /api/profile` - User profile
- `PUT /api/profile` - Update profile
- `GET /api/addresses` - User addresses
- `POST /api/addresses` - Add address
- `GET /api/cars` - User cars
- `POST /api/cars` - Add car

---

## 🎨 Status Colors

```php
'#ffc107' // Yellow - Pending
'#17a2b8' // Cyan - Confirmed
'#007bff' // Blue - Assigned
'#fd7e14' // Orange - On the way
'#6f42c1' // Purple - Arrived
'#20c997' // Teal - In progress
'#28a745' // Green - Completed
'#dc3545' // Red - Cancelled
```

---

## 🔔 Notification Types

### Captain Notifications
- `new_order_assigned` - New order assigned
- `order_cancelled` - Order cancelled by user

### User Notifications
- `captain_assigned` - Captain assigned to order
- `captain_on_way` - Captain on the way
- `captain_arrived` - Captain arrived
- `service_completed` - Service completed
- `package_expiring` - Package expiring soon (3 days)
- `package_expired` - Package expired

---

## 💾 Settings Keys

### App Settings
- `app_name` - Application name
- `app_name_ar` - Application name (Arabic)
- `app_logo` - Logo path

### Booking Settings
- `working_hours_start` - Start time (08:00)
- `working_hours_end` - End time (22:00)
- `advance_booking_minutes` - Advance booking time (30)

### Payment Settings
- `currency` - Currency code (SAR)
- `tax_rate` - Tax percentage (15)

### Notification Settings
- `firebase_server_key` - Firebase key

### Contact Settings
- `contact_phone` - Phone number
- `contact_email` - Email address
- `contact_address` - Physical address

---

## 🚀 Quick Start Checklist

- [ ] Update `.env` with database credentials
- [ ] Run `php artisan migrate:fresh --seed`
- [ ] Verify seeded data in database
- [ ] Create test captain account
- [ ] Create test user account
- [ ] Test order creation
- [ ] Test captain assignment
- [ ] Configure Firebase for notifications
- [ ] Set up cron jobs
- [ ] Implement controllers
- [ ] Build frontend

---

## 📞 Support

For issues or questions:
1. Check `DATABASE_MIGRATION_GUIDE.md`
2. Check `CONVERSION_SUMMARY.md`
3. Review model files in `app/Models/`
4. Check migration files in `database/migrations/`
