# Quick Clean - Car Cleaning Booking System

![Status](https://img.shields.io/badge/Status-Database%20Complete-success)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)
![Database](https://img.shields.io/badge/Database-MySQL-orange)

A professional car cleaning booking system built with Laravel. Converted from an e-commerce platform to a streamlined booking system with automatic captain assignment, package subscriptions, and real-time notifications.

---

## 🚀 Features

### For Customers
- 📱 **Easy Booking** - Book car cleaning services in minutes
- 📦 **Package Subscriptions** - Save money with prepaid packages
- 🚗 **Multiple Cars** - Manage multiple vehicles
- 📍 **Saved Addresses** - Quick booking with saved locations
- 💳 **Secure Payments** - Multiple payment methods supported
- ⭐ **Rate Services** - Rate captains and services
- 🔔 **Real-time Updates** - Track your order status
- 💰 **Discount Codes** - Use promo codes for discounts

### For Captains
- 📋 **Order Management** - Accept and manage orders
- 🔄 **Availability Toggle** - Control when you're available
- 📍 **GPS Navigation** - Navigate to customer locations
- 📸 **Service Photos** - Upload before/after photos
- 💵 **Earnings Tracking** - Track your earnings
- ⭐ **Rating System** - Build your reputation

### For Admins
- 📊 **Dashboard** - Comprehensive statistics and analytics
- 👥 **User Management** - Manage customers and captains
- 🛠️ **Service Management** - Create and manage services
- 📦 **Package Management** - Create and manage packages
- 📋 **Order Management** - View and manage all orders
- 💰 **Discount Management** - Create promotional codes
- ⚙️ **Settings** - Configure application settings
- 📈 **Reports** - Revenue, bookings, and performance reports

### System Features
- 🤖 **Automatic Captain Assignment** - Smart assignment based on availability
- ⏰ **Queue System** - Orders queued when no captains available
- 📅 **Schedule Management** - Working hours and advance booking
- 🔔 **Push Notifications** - Firebase Cloud Messaging integration
- 💳 **Payment Gateway** - Moyasar integration
- 🌐 **Multi-language** - Arabic and English support
- 📱 **API Ready** - RESTful API for mobile apps

---

## 📋 System Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js & NPM (for frontend assets)
- Redis (optional, for queues and caching)

---

## 🛠️ Installation

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/quick-clean.git
cd quick-clean
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quick_clean
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Setup
```bash
php artisan migrate:fresh --seed
```

This will create:
- All database tables
- Super admin account
- Order statuses
- Sample services and packages
- Car brands and models
- Application settings

### 5. Storage Link
```bash
php artisan storage:link
```

### 6. Queue Worker (Optional)
```bash
php artisan queue:work
```

### 7. Cron Jobs
Add to your crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔑 Default Credentials

### Super Admin
- **Email**: admin@quickclean.com
- **Password**: password

**⚠️ Change these credentials immediately after first login!**

---

## 📚 Documentation

- **[Database Migration Guide](DATABASE_MIGRATION_GUIDE.md)** - Complete migration guide from old structure
- **[Conversion Summary](CONVERSION_SUMMARY.md)** - What changed and why
- **[Quick Reference](QUICK_REFERENCE.md)** - Quick reference for tables, models, and queries
- **[Web Conversion Checklist](WEB_CONVERSION_CHECKLIST.md)** - Development progress checklist

---

## 🗄️ Database Structure

### Core Tables (18 Total)

**Users & Authentication**
- users, admins, captains
- password_reset_tokens, personal_access_tokens

**Services & Packages**
- services, packages, package_features, user_packages

**Bookings**
- orders, order_statuses, order_images

**Vehicles & Locations**
- cars, user_cars, user_addresses

**Payments & Discounts**
- payments, discount_codes, discount_code_services, user_discount_codes

**Ratings & Notifications**
- ratings, device_tokens, notifications, settings

**System**
- jobs, failed_jobs

---

## 🎯 Order Workflow

1. **Pending** - Order created, awaiting captain assignment
2. **Confirmed** - Order confirmed by admin (optional)
3. **Assigned** - Captain assigned to order
4. **On the way** - Captain heading to location
5. **Captain arrived** - Captain at customer location
6. **In progress** - Service in progress
7. **Completed** - Service completed
8. **Cancelled** - Order cancelled

---

## 🔄 Captain Assignment Flow

```
Order Created (Paid)
    ↓
Check Available Captains
    ↓
┌─────────────────┬─────────────────┐
│  Captain Found  │  No Captain     │
└─────────────────┴─────────────────┘
    ↓                    ↓
Assign Captain      Queue Order
    ↓                    ↓
Captain = Busy      Wait for Captain
    ↓                    ↓
Send Notification   ProcessUnassigned
    ↓                    ↓
Service Duration    Assign When Available
    ↓
Captain = Available
    ↓
Process Next Order
```

---

## 📦 Package System

### How It Works
1. User purchases a package (e.g., 10 washes for 30 days)
2. Package status = `active`
3. User books service using package
4. `remaining_washes` decremented
5. When `remaining_washes` = 0, status = `used_up`
6. When `expiry_date` passed, status = `expired`
7. User notified 3 days before expiration

### Package Types
- **Basic** - 5 washes, 30 days, 100 SAR
- **Premium** - 10 washes, 60 days, 180 SAR
- **VIP** - 15 washes, 90 days, 250 SAR

---

## 🔔 Notifications

### Firebase Cloud Messaging
Configure in `.env`:
```env
FIREBASE_SERVER_KEY=your_firebase_server_key
```

### Notification Types
- New order assigned (Captain)
- Captain on the way (User)
- Captain arrived (User)
- Service completed (User)
- Package expiring soon (User)
- Package expired (User)

---

## 💳 Payment Integration

### Moyasar Payment Gateway
Configure in `.env`:
```env
MOYASAR_API_KEY=your_api_key
MOYASAR_SECRET_KEY=your_secret_key
```

### Supported Methods
- Credit Card
- Mada
- Apple Pay
- STC Pay

---

## 🛠️ Artisan Commands

### Custom Commands
```bash
# Process unassigned orders (runs every 5 minutes via cron)
php artisan orders:process-unassigned

# Notify users about expired packages (runs daily via cron)
php artisan packages:notify-expired
```

### Database Commands
```bash
# Fresh migration with seeders
php artisan migrate:fresh --seed

# Run seeders only
php artisan db:seed

# Rollback last migration
php artisan migrate:rollback
```

### Queue Commands
```bash
# Process queue jobs
php artisan queue:work

# List failed jobs
php artisan queue:failed

# Retry all failed jobs
php artisan queue:retry all
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=OrderTest

# Run with coverage
php artisan test --coverage
```

---

## 📱 API Endpoints (Coming Soon)

### Authentication
- `POST /api/auth/register` - Register user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user

### Services & Packages
- `GET /api/services` - List services
- `GET /api/packages` - List packages
- `POST /api/packages/{id}/subscribe` - Subscribe to package

### Bookings
- `POST /api/bookings` - Create booking
- `GET /api/bookings` - User's bookings
- `POST /api/bookings/{id}/cancel` - Cancel booking
- `POST /api/bookings/{id}/rate` - Rate service

### Profile
- `GET /api/profile` - User profile
- `PUT /api/profile` - Update profile
- `GET /api/addresses` - User addresses
- `GET /api/cars` - User cars

---

## 🏗️ Project Structure

```
quick-clean/
├── app/
│   ├── Console/Commands/      # Custom artisan commands
│   ├── Http/
│   │   ├── Controllers/       # API & Web controllers
│   │   ├── Middleware/        # Custom middleware
│   │   ├── Requests/          # Form request validation
│   │   ├── Resources/         # API resources
│   │   └── Services/          # Business logic services
│   ├── Jobs/                  # Queue jobs
│   ├── Models/                # Eloquent models
│   └── Notifications/         # Notification classes
├── database/
│   ├── migrations/            # Database migrations
│   ├── seeders/               # Database seeders
│   └── migrations_backup/     # Old migrations backup
├── public/                    # Public assets
├── resources/
│   ├── views/                 # Blade templates
│   └── js/                    # Frontend JavaScript
├── routes/
│   ├── api.php               # API routes
│   ├── web.php               # Web routes
│   └── console.php           # Console routes
└── storage/                   # File storage
```

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is proprietary software. All rights reserved.

---

## 📞 Support

For support, email support@quickclean.com or create an issue in the repository.

---

## 🎉 Acknowledgments

- Laravel Framework
- Firebase Cloud Messaging
- Moyasar Payment Gateway
- All contributors and testers

---

## 📈 Roadmap

### Phase 1: Database & Models ✅
- [x] Clean database structure
- [x] Updated models
- [x] Seeders and factories
- [x] Background jobs
- [x] Console commands

### Phase 2: API Layer ⏳
- [ ] Authentication endpoints
- [ ] Service & package endpoints
- [ ] Booking endpoints
- [ ] Profile management
- [ ] Captain endpoints

### Phase 3: Frontend 🔜
- [ ] Public website
- [ ] User dashboard
- [ ] Admin panel
- [ ] Captain app

### Phase 4: Testing 🔜
- [ ] Unit tests
- [ ] Feature tests
- [ ] Integration tests

### Phase 5: Deployment 🔜
- [ ] Server setup
- [ ] CI/CD pipeline
- [ ] Monitoring
- [ ] Documentation

---

**Made with ❤️ for Quick Clean**
