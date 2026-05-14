# 🚗 Car Cleaning Services Platform - Comprehensive Business Report

## 📋 Executive Summary

This is a **multi-sided marketplace platform** connecting car owners with professional cleaning captains. The platform operates on a **service booking model** with both **pay-per-service** and **subscription package** options.

### Core Business Model
- **B2C Platform**: Customers book car cleaning services
- **Gig Economy**: Independent captains fulfill service requests
- **Dual Revenue Streams**: Per-service payments + subscription packages
- **Real-time Operations**: Live order assignment and tracking

---

## 🎯 Platform Stakeholders

### 1. **Customers (Users)**
- Book car cleaning services
- Subscribe to service packages
- Manage multiple cars
- Rate and review captains
- Track order status in real-time

### 2. **Captains (Service Providers)**
- Receive and accept service requests
- Complete cleaning jobs
- Notify customers of arrival
- Earn from completed services
- Manage availability status

### 3. **Administrators**
- Manage platform operations
- Assign captains to orders
- Monitor payments and transactions
- Handle customer support
- Generate business reports
- Send push notifications

---

## 🔄 Core Business Flows


### Flow 1: Customer Service Booking Journey

```
1. Customer Registration/Login
   ├─ POST /api/register (with phone verification)
   ├─ POST /api/verify-code
   └─ POST /api/login

2. Browse Services
   ├─ GET /api/get-services (view all services)
   ├─ GET /api/get-services/{id} (service details)
   ├─ GET /api/product-features/{id} (service features)
   └─ GET /api/get-specific-services-times/{id} (available time slots)

3. Add Car Information
   ├─ GET /api/get-cars (available car brands/models)
   └─ User adds car to profile

4. Select Service & Checkout
   ├─ POST /api/check-coupon (optional: validate discount code)
   ├─ POST /api/checkout/{service_id} (create order)
   │   ├─ Select service
   │   ├─ Choose car
   │   ├─ Select date/time
   │   ├─ Add location
   │   └─ Choose additional options (choices)
   └─ Apply coupon if available

5. Payment Processing
   ├─ POST /api/pay-order (initiate payment)
   ├─ GET /payment-page/{order_number}/{method} (payment gateway)
   └─ GET /payment-page/{number}/payment/callback (payment confirmation)

6. Order Tracking
   ├─ GET /api/user_orders (view all orders)
   ├─ GET /api/user_show_order (order details)
   └─ Receive push notifications for status updates

7. Service Completion & Rating
   ├─ Captain completes service
   ├─ GET /api/non-rating-order (orders pending rating)
   ├─ POST /api/make-rating (rate captain)
   └─ POST /api/skip-rating/{ordernumber} (skip rating)
```

---

### Flow 2: Package Subscription Journey

```
1. Browse Packages
   ├─ GET /api/packages (view all subscription packages)
   └─ GET /api/get-package/{id} (package details)

2. Subscribe to Package
   ├─ POST /api/subscribe (create subscription)
   ├─ GET /payment-package/{package_id}/{method} (payment page)
   └─ GET /payment-package/{package_id}/payment/callback (confirmation)

3. Use Package Services
   ├─ POST /api/checkout_with_package (book using package credits)
   ├─ GET /api/user-package (check remaining services)
   └─ System tracks package usage

4. Package Renewal
   ├─ POST /api/renewal-subscribe (renew expired package)
   ├─ GET /payment-renewal-subscribe/{package_id}/{method}
   └─ GET /payment-renewal-subscribe/{package_id}/payment/callback
```

---

### Flow 3: Captain Service Fulfillment Journey

```
1. Captain Registration/Login
   ├─ POST /api/captain/register
   ├─ POST /api/verify-captain
   └─ POST /api/captain/login

2. Receive Order Assignment
   ├─ Admin assigns captain via dashboard
   ├─ Captain receives push notification
   └─ GET /api/captain_orders (view assigned orders)

3. Accept & Execute Order
   ├─ GET /api/captain_show_order/{number} (order details)
   ├─ POST /api/captain_accept_order/{id} (accept order)
   ├─ POST /api/notifyArrival/{id} (notify customer of arrival)
   └─ Captain performs cleaning service

4. Complete Order
   ├─ POST /api/captain_complete_order/{id}
   ├─ Customer receives completion notification
   ├─ GET /api/completed-orders (view completed jobs)
   └─ Captain status changes to "available"

5. Captain Profile Management
   ├─ GET /api/captain-info (view profile)
   ├─ POST /api/change-captain-info (update info)
   ├─ POST /api/captain-change-profile-image
   └─ POST /api/change-password
```

---

### Flow 4: Admin Order Management Journey

```
1. Monitor Incoming Orders
   ├─ GET /dashboard/orders (view all orders)
   ├─ GET /dashboard/orders/{id} (order details)
   └─ Dashboard shows real-time order status

2. Assign Captain to Order
   ├─ PUT /dashboard/orders/{id}/assignCaptain
   ├─ System checks captain availability
   ├─ Captain status changes to "busy"
   ├─ Captain receives push notification
   └─ Order status updates to "assigned"

3. Monitor Order Progress
   ├─ Track order status changes
   ├─ View captain acceptance
   ├─ Monitor service completion
   └─ Handle customer support issues

4. Payment & Invoice Management
   ├─ GET /dashboard/payments (view all payments)
   ├─ POST /dashboard/orders/{id}/regenerate-invoice
   └─ Track payment status (paid/pending/failed)

5. Update Order Status
   ├─ PUT /dashboard/orders/{id}/update
   ├─ GET /dashboard/order_status (manage status options)
   └─ System sends notifications on status change
```

---


## 📱 Mobile API Endpoints (routes/api.php)

### Authentication & User Management

#### Customer Authentication
| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| POST | `/api/register` | Customer registration | No |
| POST | `/api/verify-code` | Verify phone OTP | No |
| POST | `/api/login` | Customer login | No |
| POST | `/api/forget-password` | Request password reset | No |
| POST | `/api/reset-password` | Reset password with code | No |
| POST | `/api/logout` | Customer logout | Yes (user) |
| POST | `/api/register-token` | Register FCM device token | Yes (user) |

#### Captain Authentication
| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| POST | `/api/captain/register` | Captain registration | No |
| POST | `/api/verify-captain` | Verify captain OTP | No |
| POST | `/api/captain/login` | Captain login | No |
| POST | `/api/captain-logout` | Captain logout | Yes (captain) |
| POST | `/api/captain-register-token` | Register FCM token | Yes (captain) |

---

### Customer Profile Management

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/user-info` | Get user profile | Yes (user) |
| POST | `/api/change-personal-info` | Update profile info | Yes (user) |
| POST | `/api/change-profile-image` | Update profile picture | Yes (user) |
| POST | `/api/client-change-password` | Change password | Yes (user) |
| GET | `/api/user-cars` | Get user's cars | Yes (user) |
| POST | `/api/delete-user-cars/{car}` | Delete a car | Yes (user) |
| GET | `/api/user-package` | Get active package | Yes (user) |

---

### Address Management

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/user-addresses` | Get all addresses | Yes (user) |
| POST | `/api/delete-address/{id}` | Delete address | Yes (user) |

---

### Service Discovery

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/get-services` | List all services | No |
| GET | `/api/get-services/{id}` | Get service details | No |
| GET | `/api/get-specific-services-times/{id}` | Get available time slots | No |
| GET | `/api/product-features/{id}` | Get service features | No |
| GET | `/api/products/{category_id}` | Get services by category | No |

---

### Package Management

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/packages` | List all packages | No |
| GET | `/api/get-package/{id}` | Get package details | No |
| POST | `/api/subscribe` | Subscribe to package | Yes (user) |
| POST | `/api/renewal-subscribe` | Renew package | Yes (user) |

---

### Checkout & Payment

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| POST | `/api/checkout/{service_id?}` | Create order (service or package) | Yes (user) |
| POST | `/api/check-coupon` | Validate discount code | Yes (user) |
| POST | `/api/apply-coupon/{orderId}` | Apply coupon to order | Yes (user) |
| POST | `/api/cancel-coupon/{orderId}` | Remove coupon from order | Yes (user) |
| POST | `/api/pay-order` | Initiate payment | Yes (user) |

---

### Order Management (Customer)

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/user_orders` | List all orders | Yes (user) |
| GET | `/api/user_show_order` | Get order details | Yes (user) |
| GET | `/api/user-canceled-orders` | List canceled orders | Yes (user) |
| POST | `/api/user-cancel-order/store` | Cancel an order | Yes (user) |
| GET | `/api/non-rating-order` | Orders pending rating | Yes (user) |
| POST | `/api/skip-rating/{ordernumber}` | Skip rating | Yes (user) |

---

### Order Management (Captain)

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/captain_orders` | List assigned orders | Yes (captain) |
| GET | `/api/completed-orders` | List completed orders | Yes (captain) |
| GET | `/api/captain_show_order/{number}` | Get order details | Yes (captain) |
| POST | `/api/captain_accept_order/{id}` | Accept order | Yes (captain) |
| POST | `/api/captain_complete_order/{id}` | Mark order complete | Yes (captain) |
| POST | `/api/notifyArrival/{id}` | Notify customer arrival | Yes (captain) |

---

### Captain Profile Management

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/captain-info` | Get captain profile | Yes (captain) |
| POST | `/api/change-captain-info` | Update captain info | Yes (captain) |
| POST | `/api/change-password` | Change password | Yes (captain) |
| POST | `/api/captain-change-profile-image` | Update profile picture | Yes (captain) |

---

### Notifications

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/client-notifications` | Get customer notifications | Yes (user) |
| GET | `/api/client-notification/show/{id}` | View notification | Yes (user) |
| POST | `/api/client-delete-notifications/{id}` | Delete notification | Yes (user) |
| POST | `/api/client-notifications/delete-all` | Delete all notifications | Yes (user) |
| GET | `/api/captain-notifications` | Get captain notifications | Yes (captain) |
| GET | `/api/captain-notification/show/{id}` | View notification | Yes (captain) |
| POST | `/api/captain-delete-notifications/{id}` | Delete notification | Yes (captain) |
| POST | `/api/captain-notifications/delete-all` | Delete all notifications | Yes (captain) |

---

### Ratings & Reviews

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| POST | `/api/make-rating` | Rate captain after service | Yes (user) |

---

### Static Content

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/get-header-banners` | Get promotional banners | No |
| GET | `/api/static-pages` | Get all static pages | No |
| GET | `/api/about` | About app page | No |
| GET | `/api/terms` | Terms & conditions | No |
| GET | `/api/common-questions` | FAQ page | No |
| GET | `/api/settings` | App settings | No |

---

### Location Data

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| GET | `/api/get-cities` | List all cities | No |
| GET | `/api/get-cars` | List car brands/models | No |

---

### Contact & Support

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| POST | `/api/contact-us` | Send contact message | No |

---

### Miscellaneous

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|---------------|
| POST | `/api/update-language` | Change app language | Yes (user/captain) |
| POST | `/api/test-notification` | Test push notification | Yes (user) |

---


## 🖥️ Admin Dashboard Endpoints (routes/dashboard_cleaned.php)

### Authentication

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/admin/login` | Show login form |
| POST | `/admin/login` | Admin login |
| POST | `/admin/logout` | Admin logout |

---

### Dashboard & Analytics

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/` | Dashboard home with statistics |
| GET | `/dashboard/reports` | Business reports & analytics |
| GET | `/dashboard/clients/export` | Export customers data |
| GET | `/dashboard/captains/export` | Export captains data |
| GET | `/dashboard/orders/export` | Export orders data |
| GET | `/dashboard/coupons/export` | Export coupons data |
| GET | `/dashboard/payments/export` | Export payments data |

---

### Order Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/orders` | List all orders |
| GET | `/dashboard/orders/{id}` | View order details |
| PUT | `/dashboard/orders/{id}/update` | Update order status |
| PUT | `/dashboard/orders/{id}/assignCaptain` | Assign captain to order |
| DELETE | `/dashboard/orders/{id}/delete` | Delete order |
| POST | `/dashboard/orders/{id}/regenerate-invoice` | Regenerate invoice PDF |

---

### Order Status Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/order_status` | List order statuses |
| GET | `/dashboard/order_status/arranging` | View status arrangement |
| PUT | `/dashboard/order_status/arranging/update` | Update status order |
| POST | `/dashboard/order_status` | Create new status |
| PUT | `/dashboard/order_status/{id}` | Update status |
| DELETE | `/dashboard/order_status/{id}` | Delete status |

---

### Payment Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/payments` | View all payments |

---

### Service Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/services` | List all services |
| GET | `/dashboard/services/create` | Create service form |
| POST | `/dashboard/services` | Store new service |
| GET | `/dashboard/services/{id}` | View service details |
| GET | `/dashboard/services/{id}/edit` | Edit service form |
| PUT | `/dashboard/services/{id}` | Update service |
| DELETE | `/dashboard/services/{id}` | Soft delete service |
| GET | `/dashboard/services/trash` | View deleted services |
| PUT | `/dashboard/services/{id}/restore` | Restore deleted service |
| DELETE | `/dashboard/services/{id}/force-delete` | Permanently delete service |

---

### Package Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/packages` | List all packages |
| GET | `/dashboard/packages/create` | Create package form |
| POST | `/dashboard/packages` | Store new package |
| GET | `/dashboard/packages/{id}` | View package details |
| GET | `/dashboard/packages/{id}/edit` | Edit package form |
| PUT | `/dashboard/packages/{id}` | Update package |
| DELETE | `/dashboard/packages/{id}` | Delete package |

---

### Additional Services (Choices)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/choices` | List additional services |
| GET | `/dashboard/choices/create` | Create choice form |
| POST | `/dashboard/choices` | Store new choice |
| GET | `/dashboard/choices/{id}/edit` | Edit choice form |
| PUT | `/dashboard/choices/{id}` | Update choice |
| DELETE | `/dashboard/choices/{id}` | Delete choice |

---

### Customer Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/clients` | List all customers |
| GET | `/dashboard/clients/create` | Create customer form |
| POST | `/dashboard/clients` | Store new customer |
| GET | `/dashboard/clients/{id}` | View customer details |
| GET | `/dashboard/clients/{id}/edit` | Edit customer form |
| PUT | `/dashboard/clients/{id}` | Update customer |
| PUT | `/dashboard/clients/{id}/update_pass` | Update customer password |
| DELETE | `/dashboard/clients/{id}` | Delete customer |

---

### Captain Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/captains` | List all captains |
| GET | `/dashboard/captains/create` | Create captain form |
| POST | `/dashboard/captains` | Store new captain |
| GET | `/dashboard/captains/{id}` | View captain details |
| GET | `/dashboard/captains/{id}/edit` | Edit captain form |
| PUT | `/dashboard/captains/{id}` | Update captain |
| PUT | `/dashboard/captains/{id}/update_pass` | Update captain password |
| GET | `/dashboard/captains/{id}/rating` | View captain ratings |
| DELETE | `/dashboard/captains/{id}` | Delete captain |

---

### Discount Code Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/discount_code` | List all discount codes |
| GET | `/dashboard/discount_code/create` | Create discount code form |
| POST | `/dashboard/discount_code` | Store new discount code |
| GET | `/dashboard/discount_code/{id}/edit` | Edit discount code form |
| PUT | `/dashboard/discount_code/{id}` | Update discount code |
| DELETE | `/dashboard/discount_code/{id}` | Delete discount code |
| GET | `/dashboard/api/search-services` | Search services for discount |

---

### Admin User Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/admins` | List all admins |
| GET | `/dashboard/admins/create` | Create admin form |
| POST | `/dashboard/admins` | Store new admin |
| GET | `/dashboard/admins/{id}/edit` | Edit admin form |
| PUT | `/dashboard/admins/{id}` | Update admin |
| PUT | `/dashboard/admins/{id}/update_password` | Update admin password |
| DELETE | `/dashboard/admins/{id}` | Delete admin |

---

### Admin Groups/Roles

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/rules` | List admin groups |
| GET | `/dashboard/rules/create` | Create group form |
| POST | `/dashboard/rules` | Store new group |
| GET | `/dashboard/rules/{id}/edit` | Edit group form |
| PUT | `/dashboard/rules/{id}` | Update group |
| DELETE | `/dashboard/rules/{id}` | Delete group |

---

### Car Brands & Models

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/cars` | List all car brands/models |
| GET | `/dashboard/cars/create` | Create car form |
| POST | `/dashboard/cars` | Store new car |
| GET | `/dashboard/cars/{id}/edit` | Edit car form |
| PUT | `/dashboard/cars/{id}` | Update car |
| DELETE | `/dashboard/cars/{id}` | Delete car |

---

### Location Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/countries` | List all countries |
| POST | `/dashboard/countries` | Store new country |
| PUT | `/dashboard/countries/{id}` | Update country |
| DELETE | `/dashboard/countries/{id}` | Delete country |
| GET | `/dashboard/countries/{countryId}/cities` | Get cities by country |
| GET | `/dashboard/cities` | List all cities |
| POST | `/dashboard/cities` | Store new city |
| PUT | `/dashboard/cities/{id}` | Update city |
| DELETE | `/dashboard/cities/{id}` | Delete city |

---

### Banners/Designs Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/designs` | List all banners |
| GET | `/dashboard/designs/create` | Create banner form |
| POST | `/dashboard/designs` | Store new banner |
| GET | `/dashboard/designs/{id}/edit` | Edit banner form |
| PUT | `/dashboard/designs/{id}` | Update banner |
| DELETE | `/dashboard/designs/{id}` | Delete banner |

---

### Contact Messages

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/contact_us_view` | List contact messages |
| GET | `/dashboard/contact_us_view/{id}/show` | View message details |

---

### Static Pages Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/pages` | List all pages |
| GET | `/dashboard/pages/create` | Create page form |
| POST | `/dashboard/pages` | Store new page |
| GET | `/dashboard/pages/{id}/edit` | Edit page form |
| PUT | `/dashboard/pages/{id}` | Update page |
| DELETE | `/dashboard/pages/{id}` | Delete page |

---

### FAQ Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/common_questions` | List all FAQs |
| GET | `/dashboard/common_questions/create` | Create FAQ form |
| POST | `/dashboard/common_questions` | Store new FAQ |
| GET | `/dashboard/common_questions/{id}/edit` | Edit FAQ form |
| PUT | `/dashboard/common_questions/{id}` | Update FAQ |
| DELETE | `/dashboard/common_questions/{id}` | Delete FAQ |

---

### System Settings

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/settings` | View system settings |
| PUT | `/dashboard/settings/{id}/update` | Update settings |

---

### Push Notifications

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/push_notification` | Push notification form |
| POST | `/dashboard/send-notification` | Send notification |
| POST | `/dashboard/send-notification-to-me` | Send test notification to self |

---

### Device Token Management

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/dashboard/device-tokens` | Store device token |
| GET | `/dashboard/device-tokens` | List all device tokens |
| DELETE | `/dashboard/device-tokens/{id}` | Delete specific token |
| DELETE | `/dashboard/device-tokens` | Delete all tokens |
| POST | `/dashboard/save-token` | Legacy token save endpoint |

---

### Admin Profile

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/profile` | View admin profile |
| PUT | `/dashboard/profile/{id}/update` | Update profile |
| GET | `/dashboard/profile/edit` | Edit profile settings |
| PUT | `/dashboard/profile/update` | Change password |

---

### Notifications

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/dashboard/notifications` | View admin notifications |

---


## 🌐 Web Routes (routes/web.php)

### Payment Gateway Integration

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/payment-page/{order_number}/{method}` | Service payment page |
| GET | `/payment-page/{number}/payment/callback` | Service payment callback |
| GET | `/payment-package/{package_id}/{method}` | Package payment page |
| GET | `/payment-package/{package_id}/payment/callback` | Package payment callback |
| GET | `/payment-renewal-subscribe/{package_id}/{method}` | Renewal payment page |
| GET | `/payment-renewal-subscribe/{package_id}/payment/callback` | Renewal callback |

### Testing

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/test-email-preview` | Preview email templates (dev only) |

---

## 💼 Business Logic & Key Features

### 1. **Dual Payment Model**

#### Pay-Per-Service
- Customer books individual services
- Pays for each service separately
- Flexible for occasional users
- No commitment required

#### Subscription Packages
- Customer subscribes to package (e.g., 10 washes/month)
- Pre-paid bulk services at discounted rate
- Package tracks remaining services
- Auto-renewal option available
- Expiration date management

### 2. **Order Lifecycle**

```
Order States Flow:
┌─────────────┐
│   Created   │ ← Customer creates order
└──────┬──────┘
       │
       ↓
┌─────────────┐
│   Pending   │ ← Awaiting payment
└──────┬──────┘
       │
       ↓
┌─────────────┐
│    Paid     │ ← Payment confirmed
└──────┬──────┘
       │
       ↓
┌─────────────┐
│  Assigned   │ ← Captain assigned by admin
└──────┬──────┘
       │
       ↓
┌─────────────┐
│  Accepted   │ ← Captain accepts order
└──────┬──────┘
       │
       ↓
┌─────────────┐
│  In Progress│ ← Captain notifies arrival
└──────┬──────┘
       │
       ↓
┌─────────────┐
│  Completed  │ ← Service finished
└──────┬──────┘
       │
       ↓
┌─────────────┐
│    Rated    │ ← Customer rates captain
└─────────────┘

Alternative Paths:
- Canceled (by customer before assignment)
- Failed Payment
- Rejected (by captain)
```

### 3. **Captain Assignment Logic**

**Manual Assignment (Current Implementation)**
- Admin views pending paid orders
- Admin selects available captain
- System checks captain status (available/busy)
- Captain receives push notification
- Captain status changes to "busy"
- Order status updates to "assigned"

**Automatic Assignment (Potential Future Feature)**
- Based on captain location proximity
- Captain availability and rating
- Workload balancing
- Service type specialization

### 4. **Discount Code System**

**Coupon Types**
- Percentage discount (e.g., 20% off)
- Fixed amount discount (e.g., 50 SAR off)
- Service-specific coupons
- First-time user coupons
- Package subscription discounts

**Coupon Validation**
- Check expiration date
- Verify usage limits
- Validate minimum order amount
- Check service applicability
- Prevent duplicate usage

### 5. **Rating & Review System**

**Rating Flow**
- Available after order completion
- 1-5 star rating for captain
- Optional text review
- Skip rating option (with tracking)
- Ratings affect captain profile
- Used for captain performance metrics

### 6. **Notification System**

**Push Notification Triggers**
- Order created (to admin)
- Captain assigned (to captain)
- Order accepted (to customer)
- Captain arrival (to customer)
- Order completed (to customer & admin)
- Payment received (to admin)
- Package expiring soon (to customer)
- New message from support

**Notification Channels**
- Firebase Cloud Messaging (FCM)
- In-app notifications
- Email notifications (for invoices)

### 7. **Multi-Language Support**

- Arabic (primary)
- English (secondary)
- User can change language preference
- Captain can change language preference
- Language stored in user/captain profile
- API responses localized based on preference

### 8. **Address Management**

- Multiple addresses per customer
- Default address selection
- Address used for service location
- Saved for future bookings
- Can be edited or deleted

### 9. **Car Management**

- Multiple cars per customer
- Car brand and model selection
- Car type affects service pricing
- Car size categories (small, medium, large, SUV)
- Service recommendations based on car type

### 10. **Invoice Generation**

- Automatic PDF invoice generation
- Sent via email after payment
- Includes order details, pricing, taxes
- Can be regenerated by admin
- Stored for record keeping

---

## 🔐 Security & Authentication

### Authentication Methods

**Customer & Captain**
- Phone number + OTP verification
- JWT token-based authentication
- Device token registration for push notifications
- Password reset via OTP
- Session management

**Admin**
- Email + password authentication
- Session-based authentication
- Role-based access control (planned)
- Password change functionality

### Middleware Protection

**API Routes**
- `auth:user` - Customer authentication
- `auth:captain` - Captain authentication
- `auth:user,captain` - Both user types
- `user_verified` - Phone verification required
- `changeLanguage` - Language preference handling

**Dashboard Routes**
- `admin` - Admin authentication required
- `guest:admin` - Only for non-authenticated admins

---

## 💳 Payment Integration

### Payment Methods Supported
- Credit/Debit Cards
- Apple Pay
- Mada (Saudi payment system)
- Other payment gateways

### Payment Flow
1. Customer initiates payment
2. Redirected to payment gateway
3. Payment processed by gateway
4. Callback received with payment status
5. Order status updated based on result
6. Invoice generated and sent
7. Notifications sent to all parties

### Payment States
- **Pending**: Awaiting payment
- **Paid**: Payment successful
- **Failed**: Payment declined/error
- **Refunded**: Payment returned (for cancellations)

---

## 📊 Reporting & Analytics

### Available Reports
- Customer export (CSV/Excel)
- Captain export (CSV/Excel)
- Orders export (CSV/Excel)
- Coupons usage export
- Payments export
- Revenue analytics
- Service popularity metrics
- Captain performance metrics

### Dashboard Statistics
- Total orders (today, week, month)
- Revenue metrics
- Active customers count
- Active captains count
- Pending orders
- Completed orders
- Average rating
- Package subscriptions

---

## 🔄 Integration Points

### Third-Party Services

**Firebase Cloud Messaging**
- Push notifications to mobile apps
- Web push notifications
- Device token management
- Notification delivery tracking

**Payment Gateway**
- Payment processing
- Refund handling
- Transaction verification
- Webhook callbacks

**Email Service**
- Invoice delivery
- Password reset emails
- Order confirmation emails
- Marketing emails (potential)

**SMS Service** (Implied)
- OTP verification codes
- Order status updates
- Marketing messages (potential)

---


## 🎯 Key Business Rules

### Order Rules
1. **Payment Required Before Assignment**: Orders must be paid before captain assignment
2. **Single Captain Per Order**: One order can only be assigned to one captain
3. **Captain Availability**: Only available captains can be assigned
4. **Order Cancellation**: Customers can cancel before captain assignment
5. **Rating Requirement**: Customers should rate after service completion
6. **Package Usage**: Package services deduct from remaining count

### Package Rules
1. **Expiration Date**: Packages have validity period
2. **Service Limit**: Fixed number of services per package
3. **No Refund**: Unused services expire with package
4. **Renewal Option**: Expired packages can be renewed
5. **Single Active Package**: User can have only one active package

### Captain Rules
1. **Verification Required**: Captains must be verified before activation
2. **Status Management**: Captain status (available/busy) auto-updates
3. **Order Acceptance**: Captain must accept assigned orders
4. **Completion Confirmation**: Captain marks order as complete
5. **Rating Impact**: Low ratings may affect future assignments

### Discount Code Rules
1. **Single Use Per Order**: One coupon per order
2. **Expiration Check**: Expired coupons rejected
3. **Minimum Order**: Some coupons require minimum amount
4. **Service Specific**: Some coupons apply to specific services
5. **Usage Limit**: Coupons have maximum usage count

---

## 📈 Revenue Streams

### Primary Revenue
1. **Service Fees**: Commission on each service booking
2. **Package Sales**: Subscription package purchases
3. **Premium Services**: Additional service options (choices)

### Potential Revenue
1. **Captain Commission**: Percentage of service fee
2. **Featured Listings**: Promoted services in app
3. **Advertising**: Banner ads for car-related products
4. **Premium Packages**: Higher-tier subscription options
5. **Corporate Accounts**: B2B fleet cleaning services

---

## 🔧 Technical Architecture

### Backend Stack
- **Framework**: Laravel (PHP)
- **Database**: MySQL/PostgreSQL
- **Authentication**: JWT + Session
- **Queue System**: Laravel Queues (for notifications, emails)
- **Storage**: Local/S3 for images and invoices
- **Cache**: Redis (implied for performance)

### API Architecture
- **RESTful API**: Standard REST endpoints
- **JSON Responses**: Consistent API response format
- **Middleware**: Authentication, language, verification
- **Rate Limiting**: API throttling (implied)
- **Versioning**: API version management (potential)

### Mobile App Integration
- **Platform**: iOS & Android (implied)
- **Push Notifications**: FCM integration
- **Real-time Updates**: Polling or WebSockets
- **Offline Support**: Local data caching
- **Deep Linking**: Payment callback handling

---

## 🚀 Operational Workflows

### Daily Operations

**Morning**
1. Review overnight orders
2. Check pending payments
3. Assign captains to paid orders
4. Monitor captain availability

**During Day**
1. Real-time order monitoring
2. Handle customer support messages
3. Resolve payment issues
4. Manage captain assignments
5. Track order completions

**Evening**
1. Review daily statistics
2. Process refunds if needed
3. Send reminder notifications
4. Prepare next day assignments

### Weekly Operations
1. Generate weekly reports
2. Review captain performance
3. Analyze service popularity
4. Update discount codes
5. Review customer feedback
6. Plan marketing campaigns

### Monthly Operations
1. Financial reconciliation
2. Captain performance reviews
3. Service pricing adjustments
4. Package renewal campaigns
5. System maintenance
6. Feature updates deployment

---

## 📱 User Experience Flows

### Customer Journey Map

**Discovery Phase**
- Download app
- Browse services without registration
- View pricing and packages
- Read reviews and ratings

**Registration Phase**
- Enter phone number
- Receive OTP
- Verify phone
- Complete profile
- Add car details

**Booking Phase**
- Select service
- Choose date/time
- Add location
- Select car
- Add extras (choices)
- Apply coupon
- Review order

**Payment Phase**
- Choose payment method
- Complete payment
- Receive confirmation
- Get invoice via email

**Service Phase**
- Track captain assignment
- Receive arrival notification
- Service performed
- Completion notification

**Post-Service Phase**
- Rate captain
- View invoice
- Book again or subscribe

### Captain Journey Map

**Onboarding Phase**
- Register as captain
- Verify phone
- Complete profile
- Upload documents
- Wait for admin approval

**Active Phase**
- Receive order notification
- View order details
- Accept order
- Navigate to location
- Notify arrival
- Perform service
- Mark complete

**Growth Phase**
- Build rating
- Increase earnings
- Get more assignments
- Receive bonuses (potential)

---

## 🎨 Content Management

### Manageable Content
1. **Services**: Add, edit, delete services
2. **Packages**: Manage subscription packages
3. **Banners**: Promotional banners
4. **Static Pages**: About, Terms, Privacy
5. **FAQs**: Common questions
6. **Settings**: App configuration
7. **Discount Codes**: Coupon management
8. **Notifications**: Push notification templates

### Dynamic Content
1. **Order Status**: Customizable status names
2. **Service Times**: Available time slots
3. **Pricing**: Service and package pricing
4. **Cities**: Service coverage areas
5. **Car Models**: Supported car types

---

## 🔍 Search & Discovery

### Customer Search
- Browse all services
- Filter by category
- Search by service name
- View popular services
- See recommended services

### Admin Search
- Search orders by number
- Filter orders by status
- Search customers by name/phone
- Search captains by name
- Search services for discount codes

---

## 📞 Customer Support

### Support Channels
1. **Contact Form**: In-app contact us
2. **Phone Support**: Direct call option
3. **Email**: Support email address
4. **FAQ**: Self-service help
5. **In-app Chat**: (Potential feature)

### Support Workflows
1. Customer submits message
2. Admin receives notification
3. Admin views message in dashboard
4. Admin responds (external communication)
5. Issue tracked and resolved

---

## 🎯 Success Metrics (KPIs)

### Business Metrics
- **GMV**: Gross Merchandise Value
- **Revenue**: Total platform revenue
- **Order Volume**: Number of orders
- **Average Order Value**: Revenue per order
- **Customer Lifetime Value**: Long-term customer value
- **Package Conversion Rate**: Free to paid conversion

### Operational Metrics
- **Order Completion Rate**: % of completed orders
- **Captain Utilization**: % of time captains are busy
- **Average Service Time**: Time per service
- **Customer Retention**: Repeat customer rate
- **Captain Retention**: Active captain rate

### Quality Metrics
- **Average Rating**: Overall service rating
- **Customer Satisfaction**: NPS score
- **Complaint Rate**: Support tickets per order
- **Cancellation Rate**: % of canceled orders
- **On-time Completion**: % of on-time services

---

## 🚧 Potential Improvements

### Short-term Enhancements
1. **Automatic Captain Assignment**: AI-based assignment
2. **Real-time Tracking**: GPS tracking of captain
3. **In-app Chat**: Customer-captain communication
4. **Wallet System**: Store credits and refunds
5. **Referral Program**: Customer referral rewards

### Medium-term Features
1. **Subscription Management**: Auto-renewal, pause, cancel
2. **Corporate Accounts**: B2B fleet management
3. **Loyalty Program**: Points and rewards
4. **Advanced Analytics**: Predictive analytics
5. **Multi-language Expansion**: More languages

### Long-term Vision
1. **Marketplace Expansion**: Additional services
2. **Franchise Model**: Regional partnerships
3. **White-label Solution**: Platform licensing
4. **AI Recommendations**: Personalized suggestions
5. **IoT Integration**: Smart car integration

---

## 🔒 Compliance & Legal

### Data Protection
- User data encryption
- GDPR compliance (if applicable)
- Data retention policies
- Privacy policy
- Terms of service

### Financial Compliance
- Payment gateway PCI compliance
- Invoice generation
- Tax calculation
- Financial reporting
- Refund policies

### Operational Compliance
- Service agreements
- Captain contracts
- Insurance requirements
- Quality standards
- Dispute resolution

---

## 📚 Documentation Status

### Existing Documentation
- ✅ API Routes documented
- ✅ Dashboard routes documented
- ✅ Payment flows documented
- ✅ Firebase setup guide
- ✅ Device token API guide

### Missing Documentation
- ⚠️ Database schema documentation
- ⚠️ API response formats
- ⚠️ Error code reference
- ⚠️ Deployment guide
- ⚠️ Testing procedures

---

## 🎓 Conclusion

This is a **comprehensive on-demand service marketplace** for car cleaning services with:

### Core Strengths
✅ **Dual Revenue Model**: Pay-per-service + subscriptions
✅ **Complete Order Management**: End-to-end order lifecycle
✅ **Multi-stakeholder Platform**: Customers, captains, admins
✅ **Real-time Operations**: Push notifications and live updates
✅ **Flexible Payment**: Multiple payment methods
✅ **Quality Control**: Rating and review system
✅ **Scalable Architecture**: Laravel-based robust backend

### Business Model
- **Commission-based**: Platform takes percentage of each transaction
- **Subscription Revenue**: Package sales provide recurring revenue
- **Marketplace Model**: Connects service providers with customers
- **Gig Economy**: Independent captains, flexible work

### Target Market
- **Primary**: Car owners needing regular cleaning
- **Secondary**: Fleet owners, corporate accounts
- **Geographic**: City-based service coverage
- **Demographics**: Middle to upper-income car owners

### Competitive Advantages
1. **Convenience**: On-demand booking
2. **Quality**: Verified captains with ratings
3. **Flexibility**: Pay-per-use or subscription
4. **Technology**: Mobile-first platform
5. **Trust**: Secure payments and insurance

---

**Report Generated**: May 14, 2026
**Platform Status**: Production
**Version**: Current

