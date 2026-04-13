# Admin Panel Fixes - Product to Service Migration

## ✅ Completed Fixes

1. **DashboardController.php** ✅
   - Changed `Product::count()` to `Service::count()`
   - Added `Package::count()`, `Captain::count()`
   - Added statistics: todayOrders, availableCaptains, busyCaptains, totalRevenue

2. **dashboard.blade.php** ✅
   - Updated view to show Services, Packages, Captains
   - Added revenue statistics
   - Added captain availability status

3. **CheckoutService.php** ✅
   - Changed `Product::findOrFail()` to `Service::findOrFail()`
   - Updated imports

## 🔧 Files That Need Fixing

### High Priority (Breaking Errors)

#### Controllers
1. **app/Http/Controllers/Api/ServicesController.php**
   - Line 25: `Product::where('status', 'active')` → `Service::where('is_active', true)`
   - Line 37: `Product::findOrFail()` → `Service::findOrFail()`
   - Line 61: `Product::findOrFail()` → `Service::findOrFail()`

2. **app/Http/Controllers/Api/PaymentController.php**
   - Line 108: `Product::where('id', $order->product_id)` → `Service::where('id', $order->service_id)`

3. **app/Http/Controllers/Dashboard/ProductsController.php**
   - Rename to: `ServicesController.php`
   - Replace all `Product` references with `Service`
   - Update routes

#### Routes
4. **routes/dashboard.php**
   - Line 26: Change `use App\Http\Controllers\Dashboard\ProductsController;` to `ServicesController`
   - Lines 93-95: Update product routes to service routes
   - Lines 115-119: Update product routes

5. **routes/web.php**
   - Remove all commented product routes (cleanup)

### Medium Priority (May Cause Issues)

#### Models
6. **app/Models/OrderItem.php**
   - Line 19: `belongsTo(Product::class)` → `belongsTo(Service::class)`
   - Rename `product_id` references to `service_id`

7. **app/Models/Choice.php**
   - Line 32: Update relationship if choices are linked to services

#### API Controllers
8. **app/Http/Controllers/Api/CheckoutController.php**
   - Line 167: `Product::findOrFail()` → `Service::findOrFail()`

9. **app/Http/Controllers/Api/FilterProductsController.php**
   - Rename to `FilterServicesController.php`
   - Replace `Product::query()` with `Service::query()`

10. **app/Http/Controllers/Api/SearchProductController.php**
    - Rename to `SearchServiceController.php`
    - Line 16: `Product::when()` → `Service::when()`

11. **app/Http/Controllers/Api/ProductDetailsController.php**
    - Rename to `ServiceDetailsController.php`
    - Replace all `Product::find()` with `Service::find()`

### Low Priority (Old/Unused Code)

#### Repositories
12. **app/Repositories/Reports/ReportsRepository.php**
    - Line 23: `Product::latest()` → `Service::latest()`

#### Livewire Components
13. **app/Livewire/Products/** (entire folder)
    - Rename to `Services/`
    - Update all Product references

#### Old Controllers (Can be deleted)
14. **app/Http/Controllers/Api/LatestProductController.php** - DELETE
15. **app/Http/Controllers/Api/DiscountsController.php** - DELETE
16. **app/Http/Controllers/Api/GetAllFavProductsController.php** - DELETE
17. **app/Http/Controllers/Front/CartController.php** - DELETE
18. **app/Http/Controllers/Api/CartController.php** - DELETE
19. **app/Http/Controllers/Api/guest/GuestCartController.php** - DELETE

### Models to Delete (E-commerce Related)
20. **app/Models/Cart.php** - DELETE
21. **app/Models/Color.php** - DELETE
22. **app/Models/ProductImage.php** - DELETE
23. **app/Models/ProductFeature.php** - DELETE
24. **app/Models/Guest.php** - DELETE
25. **app/Models/MainCategory.php** - DELETE
26. **app/Models/MainCategorySetting.php** - DELETE
27. **app/Models/SubSettings.php** - DELETE
28. **app/Models/Company.php** - DELETE

## 📋 Quick Fix Script

### Step 1: Update Main Service Controller
```bash
# Rename ProductsController to ServicesController
mv app/Http/Controllers/Dashboard/ProductsController.php app/Http/Controllers/Dashboard/ServicesController.php
```

### Step 2: Update API Controllers
```bash
# Update ServicesController
# Update PaymentController
# Update CheckoutController
```

### Step 3: Update Routes
```bash
# Edit routes/dashboard.php
# Edit routes/api.php
```

### Step 4: Delete Unused Files
```bash
# Delete old e-commerce controllers
# Delete old models
# Delete old views
```

## 🎯 Recommended Approach

### Phase 1: Fix Critical Errors (Now)
1. ✅ DashboardController
2. ✅ Dashboard view
3. ✅ CheckoutService
4. 🔧 ServicesController (API)
5. 🔧 PaymentController
6. 🔧 Dashboard routes

### Phase 2: Update API (Next)
1. Rename and update all API controllers
2. Update API routes
3. Test API endpoints

### Phase 3: Cleanup (Later)
1. Delete unused models
2. Delete unused controllers
3. Delete unused views
4. Clean up routes

## 🚀 Testing Checklist

After fixes:
- [ ] Dashboard loads without errors
- [ ] Can view services list
- [ ] Can view packages list
- [ ] Can view orders list
- [ ] Can view captains list
- [ ] Can create new order
- [ ] API endpoints work
- [ ] Payment processing works

## 📝 Notes

- The old `products` table is now `services`
- The old `product_id` is now `service_id` in orders
- Packages are separate from services
- No more cart/wishlist functionality
- No more categories/subcategories
- Focus is on booking services, not selling products

## 🔗 Related Files

- **Models**: Service, Package, Order, Captain, User
- **Controllers**: ServicesController, PackagesController, OrdersController, CaptainsController
- **Views**: dashboard/services/, dashboard/packages/, dashboard/orders/, dashboard/captains/
- **Routes**: routes/dashboard.php, routes/api.php

---

**Status**: Dashboard fixed, API controllers need updating
**Priority**: Fix ServicesController and routes next
**Timeline**: 2-3 hours for complete migration
