# 🚀 Quick Start Guide - Quick Clean Admin Dashboard

## 🔐 Admin Login

```
URL: http://127.0.0.1:8000/admin/login
Email: admin@admin.com
Password: 12345678
```

---

## 📊 Dashboard Overview

After login, you'll see:
- **Services Count**: Total car cleaning services
- **Packages Count**: Total subscription packages
- **Orders**: Today, This Month, Total
- **Captains**: Total, Available, Busy
- **Revenue**: Total earnings
- **Messages**: Contact form submissions

---

## 🗺️ Dashboard Menu Guide

### 🏠 Services & Packages
- **Services** (`/dashboard/services`) - Manage car cleaning services
- **Additional Services** (`/dashboard/main_choices`) - Extra services (wax, polish, etc.)
- **Packages** (`/dashboard/packages`) - Subscription packages

### 👔 Captains
- **Staff List** (`/dashboard/captains`) - Manage cleaning staff
  - View availability (available/busy)
  - View ratings
  - Assign to orders

### 🎨 Banners & Design
- **Animated Banners** (`/dashboard/designs`) - App banners

### 💳 Bookings & Orders
- **Orders** (`/dashboard/orders`) - View and manage bookings
- **Payments** (`/dashboard/payments`) - Payment history

### 👥 Clients
- **Client List** (`/dashboard/clients`) - Customer management

### ⚙️ Settings
- **App Settings** (`/dashboard/settings`) - Working hours, rest time, payment keys
- **Cities** (`/dashboard/cities`) - Service areas
- **Cars** (`/dashboard/cars`) - Car types
- **Discount Codes** (`/dashboard/discount_code`) - Promo codes

### 👨‍💼 Admins
- **Admin List** (`/dashboard/admins`) - Admin users
- **Groups** (`/dashboard/rules`) - Permission groups

### ✉️ Contact Messages
- **Messages** (`/dashboard/contact_us`) - Customer inquiries

### 📄 Pages
- **App Pages** (`/dashboard/pages`) - Static pages (About, Terms, etc.)

### 🔔 Notifications
- **Client Notifications** (`/dashboard/push_notification`) - Send push notifications

### 📊 Reports
- **Reports** (`/dashboard/reports`) - Analytics and exports

---

## 🔧 Common Tasks

### Create a New Service
1. Go to **Services & Packages** → **Services**
2. Click **Create New**
3. Fill in:
   - Name (Arabic & English)
   - Description
   - Price
   - Duration (HH:MM format)
   - Image
4. Click **Save**

### Create a Package
1. Go to **Services & Packages** → **Packages**
2. Click **Create New**
3. Fill in:
   - Name
   - Description
   - Number of services
   - Validity days
   - Price
4. Click **Save**

### Assign Captain to Order
1. Go to **Bookings & Orders** → **Orders**
2. Click on order
3. Select captain from dropdown
4. Click **Assign**

### Create Discount Code
1. Go to **Settings** → **Discount Codes**
2. Click **Create New**
3. Fill in:
   - Code
   - Discount type (percentage/fixed)
   - Amount
   - Valid from/to
   - Usage limit
   - Select services (optional)
4. Click **Save**

---

## 🎯 Key Features

### Automatic Captain Assignment
- When order is paid, system automatically assigns available captain
- Captain status changes to "busy"
- After service duration, captain becomes "available" again

### Package System
- Clients can buy prepaid packages
- Each booking deducts from package
- Packages expire after validity period
- System sends expiry notifications

### Time Slot Management
- System shows available time slots
- Considers working hours and rest time
- Prevents double booking
- Shows captain availability

### Payment Integration
- Moyasar payment gateway
- Supports: Credit Card, Mada, Apple Pay
- Automatic invoice generation
- Payment status tracking

---

## 🛠️ Useful Commands

### Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Check Database
```bash
php artisan migrate:status
php artisan db:show
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Run Queue Worker (for captain assignment)
```bash
php artisan queue:work
```

---

## 📱 API Endpoints (for mobile app)

### Services
```
GET /api/get-services - List all services
GET /api/get-services/{id} - Get service details
GET /api/get-specific-services-times/{id} - Get available time slots
```

### Packages
```
GET /api/packages - List all packages
GET /api/get-package/{id} - Get package details
POST /api/subscribe - Subscribe to package
```

### Orders
```
POST /api/checkout/{service_id} - Create order
POST /api/check-coupon - Validate discount code
POST /api/apply-coupon/{orderId} - Apply discount
```

### Authentication
```
POST /api/register - User registration
POST /api/login - User login
POST /api/logout - User logout
```

---

## ⚠️ Important Notes

### Super Admin
- Email: admin@admin.com
- Has ALL permissions
- Can access everything

### Regular Admins
- Currently have all permissions (temporary)
- TODO: Implement role-based permissions

### Database
- Name: `car_cleaner`
- User: `root`
- Password: (empty)

### Working Hours
- Set in Settings → App Settings
- Format: HH:MM (24-hour)
- Example: 09:00 to 21:00

### Rest Time
- Set in Settings → App Settings
- Example: 13:00 to 15:00
- No bookings during rest time

---

## 🐛 Troubleshooting

### Dashboard Not Loading
```bash
# Clear all caches
php artisan optimize:clear

# Check .env file
cat .env | grep DB_

# Restart server
php artisan serve
```

### Permission Denied
- Check if admin has `is_super_admin = 1`
- Or implement role-based permissions

### 404 on Services Page
```bash
php artisan route:cache
php artisan route:list | grep services
```

### Database Connection Error
- Check `.env` file
- Verify MySQL is running
- Check database name: `car_cleaner`

---

## 📚 Documentation Files

- **COMPLETE_SESSION_SUMMARY.md** - Full transformation details
- **DASHBOARD_REBUILD_COMPLETE.md** - Dashboard changes
- **FIXES_COMPLETED.md** - Product → Service migration
- **LIVEWIRE_DISABLED.md** - Livewire removal
- **PERMISSIONS_FIXED.md** - Permission system
- **QUICK_START_GUIDE.md** - This file

---

## ✅ System Status

- ✅ Database: Clean and optimized
- ✅ Backend: Fully functional
- ✅ Admin Panel: Rebuilt and organized
- ✅ API: Updated and working
- ✅ Documentation: Comprehensive
- ✅ Ready for: Testing & Development

---

## 🎉 You're Ready!

The Quick Clean admin dashboard is fully functional and ready to use. Start by:

1. ✅ Login to admin panel
2. ✅ Check dashboard statistics
3. ✅ Create some test services
4. ✅ Create a test package
5. ✅ Add a captain
6. ✅ Test the booking flow

**Happy Managing! 🚗✨**

---

**Last Updated**: 2026-04-13  
**Version**: 2.0 (Booking System)  
**Status**: Production Ready (after testing)
