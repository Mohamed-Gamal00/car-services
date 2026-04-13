# Quick Clean - System Architecture

## 🏗️ System Overview

```
┌─────────────────────────────────────────────────────────────┐
│                     Quick Clean System                       │
│                  Car Cleaning Booking Platform               │
└─────────────────────────────────────────────────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
   ┌────▼────┐          ┌────▼────┐          ┌────▼────┐
   │  Users  │          │ Captains│          │ Admins  │
   │ (Web/App)│          │  (App)  │          │  (Web)  │
   └────┬────┘          └────┬────┘          └────┬────┘
        │                     │                     │
        └─────────────────────┼─────────────────────┘
                              │
                    ┌─────────▼─────────┐
                    │   Laravel API     │
                    │  (RESTful API)    │
                    └─────────┬─────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
   ┌────▼────┐          ┌────▼────┐          ┌────▼────┐
   │ Business│          │  Queue  │          │Firebase │
   │ Logic   │          │  Jobs   │          │   FCM   │
   └────┬────┘          └────┬────┘          └─────────┘
        │                     │
        └─────────────────────┼─────────────────────┐
                              │                     │
                    ┌─────────▼─────────┐    ┌─────▼─────┐
                    │   MySQL Database  │    │  Payment  │
                    │   (18 Tables)     │    │  Gateway  │
                    └───────────────────┘    └───────────┘
```

---

## 📊 Database Architecture

### Entity Relationship Diagram

```
┌──────────┐         ┌──────────┐         ┌──────────┐
│  Users   │────────▶│  Orders  │◀────────│ Captains │
└──────────┘         └──────────┘         └──────────┘
     │                    │                      │
     │                    │                      │
     ▼                    ▼                      ▼
┌──────────┐         ┌──────────┐         ┌──────────┐
│UserPackage│        │  Ratings │         │DeviceToken│
└──────────┘         └──────────┘         └──────────┘
     │                    
     │                    
     ▼                    
┌──────────┐         ┌──────────┐         ┌──────────┐
│ Packages │         │ Services │         │   Cars   │
└──────────┘         └──────────┘         └──────────┘
     │                    │                      │
     │                    │                      │
     ▼                    ▼                      ▼
┌──────────┐         ┌──────────┐         ┌──────────┐
│PackageFeature│      │DiscountCode│       │UserCars  │
└──────────┘         └──────────┘         └──────────┘
```

### Core Relationships

**User Relationships:**
- User → Orders (1:N)
- User → UserPackages (1:N)
- User → UserAddresses (1:N)
- User → Cars (N:M via user_cars)
- User → Ratings (1:N)
- User → Payments (1:N)
- User → DeviceTokens (1:N polymorphic)

**Captain Relationships:**
- Captain → Orders (1:N)
- Captain → Ratings (1:N)
- Captain → DeviceTokens (1:N polymorphic)

**Order Relationships:**
- Order → User (N:1)
- Order → Service (N:1)
- Order → UserPackage (N:1)
- Order → Captain (N:1)
- Order → Car (N:1)
- Order → OrderStatus (N:1)
- Order → OrderImages (1:N)
- Order → Rating (1:1)
- Order → Payments (1:N polymorphic)

**Package Relationships:**
- Package → UserPackages (1:N)
- Package → PackageFeatures (1:N)

**Service Relationships:**
- Service → Orders (1:N)
- Service → DiscountCodes (N:M)

---

## 🔄 Booking Flow Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Booking Flow                              │
└─────────────────────────────────────────────────────────────┘

1. User Initiates Booking
   ├─ Select Service OR Use Package
   ├─ Choose Date & Time
   ├─ Select Car
   ├─ Select Address
   └─ Apply Discount (optional)
          │
          ▼
2. Validation
   ├─ Check Working Hours
   ├─ Check Advance Booking Time (30 min)
   ├─ Validate Package (if used)
   │  ├─ Check Status (active)
   │  ├─ Check Expiry Date
   │  └─ Check Remaining Washes
   └─ Validate Discount Code (if used)
          │
          ▼
3. Payment Processing
   ├─ If Package: Skip Payment
   └─ If Service: Process Payment
      ├─ Create Payment Record
      ├─ Call Payment Gateway
      └─ Wait for Confirmation
          │
          ▼
4. Order Creation
   ├─ Create Order Record
   ├─ Generate Order Number
   ├─ Set Status = Pending
   └─ If Package: Decrement Remaining Washes
          │
          ▼
5. Captain Assignment
   ├─ Search Available Captains
   │  ├─ Status = 'available'
   │  └─ is_active = true
   │
   ├─ If Captain Found:
   │  ├─ Assign Captain to Order
   │  ├─ Update Order Status = Assigned
   │  ├─ Update Captain Status = Busy
   │  ├─ Send Notification to Captain
   │  └─ Schedule MakeCaptainAvailableJob
   │     (Delay = Service Duration)
   │
   └─ If No Captain:
      ├─ Order Remains Unassigned
      └─ ProcessUnassignedOrders Command
         (Runs every 5 minutes)
          │
          ▼
6. Service Execution
   ├─ Captain Accepts Order
   ├─ Captain On The Way
   ├─ Captain Arrives
   ├─ Service In Progress
   ├─ Captain Uploads Photos
   └─ Service Completed
          │
          ▼
7. Post-Service
   ├─ Captain Status = Available
   ├─ Process Next Unassigned Order
   ├─ User Rates Service
   └─ Generate Invoice
```

---

## 🤖 Captain Assignment System

```
┌─────────────────────────────────────────────────────────────┐
│              Captain Assignment Architecture                 │
└─────────────────────────────────────────────────────────────┘

Order Created (payment_status = 'paid')
          │
          ▼
┌─────────────────────────┐
│ Find Available Captain  │
│ - status = 'available'  │
│ - is_active = true      │
└───────────┬─────────────┘
            │
    ┌───────┴───────┐
    │               │
    ▼               ▼
┌─────────┐   ┌─────────┐
│ Found   │   │Not Found│
└────┬────┘   └────┬────┘
     │             │
     │             ▼
     │        ┌─────────────────┐
     │        │ Queue Order     │
     │        │ captain_id=NULL │
     │        └────┬────────────┘
     │             │
     │             ▼
     │        ┌─────────────────────────┐
     │        │ProcessUnassignedOrders  │
     │        │(Cron: Every 5 minutes)  │
     │        └────┬────────────────────┘
     │             │
     │             └──────┐
     │                    │
     ▼                    ▼
┌──────────────────────────────────┐
│ Assign Captain                   │
│ - order.captain_id = captain.id  │
│ - order.status_id = 3 (Assigned) │
│ - captain.status = 'busy'        │
└────────────┬─────────────────────┘
             │
             ▼
┌──────────────────────────────────┐
│ Send Firebase Notification       │
│ - Title: "New Order"             │
│ - Body: "You have a new request" │
│ - Data: {order_id: X}            │
└────────────┬─────────────────────┘
             │
             ▼
┌──────────────────────────────────┐
│ Schedule MakeCaptainAvailableJob │
│ - Delay: Service Duration        │
│ - Job: Set captain.status =      │
│   'available'                    │
└────────────┬─────────────────────┘
             │
             ▼
┌──────────────────────────────────┐
│ Service Execution                │
│ - Captain performs service       │
│ - Updates order status           │
│ - Uploads photos                 │
└────────────┬─────────────────────┘
             │
             ▼
┌──────────────────────────────────┐
│ Service Completed                │
│ - MakeCaptainAvailableJob runs   │
│ - captain.status = 'available'   │
│ - Trigger AssignCaptainToOrder   │
└──────────────────────────────────┘
             │
             ▼
┌──────────────────────────────────┐
│ Process Next Unassigned Order    │
│ (If any exist)                   │
└──────────────────────────────────┘
```

---

## 📦 Package System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                  Package System Flow                         │
└─────────────────────────────────────────────────────────────┘

1. User Subscribes to Package
   ├─ Select Package
   ├─ Process Payment
   └─ Create UserPackage
      ├─ status = 'inactive'
      ├─ remaining_washes = package.wash_count
      ├─ start_date = today
      └─ expiry_date = today + validity_days
          │
          ▼
2. Payment Confirmed
   └─ Update UserPackage
      └─ status = 'active'
          │
          ▼
3. User Books Service
   ├─ Select Package (instead of service)
   ├─ Validate Package
   │  ├─ status = 'active'
   │  ├─ expiry_date >= today
   │  └─ remaining_washes > 0
   └─ Create Order
      ├─ user_package_id = package.id
      ├─ payment_method = 'package'
      └─ payment_status = 'paid'
          │
          ▼
4. After Order Created
   └─ Decrement Package
      ├─ remaining_washes -= 1
      └─ Check Status
         ├─ If remaining_washes = 0
         │  └─ status = 'used_up'
         └─ If expiry_date < today
            └─ status = 'expired'
          │
          ▼
5. Package Expiration Monitoring
   └─ NotifyExpiredPackages Command
      (Runs daily via cron)
      │
      ├─ Find Expiring Soon (3 days)
      │  ├─ Send Notification
      │  └─ notified_expired = true
      │
      └─ Find Expired Today
         ├─ Send Notification
         ├─ status = 'expired'
         └─ notified_expired = true
```

---

## 💳 Payment Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                   Payment Flow                               │
└─────────────────────────────────────────────────────────────┘

1. User Initiates Payment
   ├─ For Service Booking
   └─ For Package Subscription
          │
          ▼
2. Create Payment Record
   ├─ user_id
   ├─ payable_type (Order/UserPackage)
   ├─ payable_id
   ├─ amount
   ├─ method (credit_card/mada/apple_pay)
   ├─ status = 'pending'
   └─ reference = UUID
          │
          ▼
3. Call Payment Gateway (Moyasar)
   ├─ Send Payment Request
   ├─ Redirect to Payment Page
   └─ User Completes Payment
          │
          ▼
4. Payment Gateway Callback
   ├─ Receive Payment Response
   ├─ Verify Payment
   └─ Update Payment Record
      ├─ payment_id = gateway_id
      ├─ status = 'completed'/'failed'
      ├─ gateway_response = JSON
      └─ paid_at = timestamp
          │
          ▼
5. Process Payment Result
   │
   ├─ If Success:
   │  ├─ Update Order/Package
   │  │  └─ payment_status = 'paid'
   │  ├─ Trigger Captain Assignment
   │  └─ Send Confirmation
   │
   └─ If Failed:
      ├─ Update Order/Package
      │  └─ payment_status = 'failed'
      └─ Send Failure Notification
```

---

## 🔔 Notification Architecture

```
┌─────────────────────────────────────────────────────────────┐
│              Notification System                             │
└─────────────────────────────────────────────────────────────┘

Notification Trigger
          │
          ▼
┌─────────────────────────┐
│ Identify Recipients     │
│ - User or Captain       │
│ - Get Device Tokens     │
└───────────┬─────────────┘
            │
            ▼
┌─────────────────────────┐
│ Prepare Notification    │
│ - Title                 │
│ - Body                  │
│ - Data (JSON)           │
│ - Localize (AR/EN)      │
└───────────┬─────────────┘
            │
            ▼
┌─────────────────────────┐
│ Send via Firebase FCM   │
│ - Multiple Tokens       │
│ - Platform Specific     │
└───────────┬─────────────┘
            │
            ▼
┌─────────────────────────┐
│ Log Notification        │
│ - Success/Failure       │
│ - Timestamp             │
└─────────────────────────┘

Notification Types:
├─ Captain Notifications
│  ├─ New Order Assigned
│  └─ Order Cancelled
│
└─ User Notifications
   ├─ Captain Assigned
   ├─ Captain On The Way
   ├─ Captain Arrived
   ├─ Service Completed
   ├─ Package Expiring (3 days)
   └─ Package Expired
```

---

## 🔐 Security Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                  Security Layers                             │
└─────────────────────────────────────────────────────────────┘

1. Authentication Layer
   ├─ Laravel Sanctum (API Tokens)
   ├─ Password Hashing (bcrypt)
   └─ Token Expiration

2. Authorization Layer
   ├─ Middleware (auth, admin, captain)
   ├─ Policy Classes
   └─ Gate Definitions

3. Validation Layer
   ├─ Form Requests
   ├─ Input Sanitization
   └─ Business Logic Validation

4. Database Layer
   ├─ Foreign Key Constraints
   ├─ Unique Constraints
   └─ Enum Constraints

5. API Layer
   ├─ Rate Limiting
   ├─ CORS Configuration
   └─ API Versioning

6. Infrastructure Layer
   ├─ HTTPS/SSL
   ├─ Firewall Rules
   └─ Environment Variables
```

---

## 📈 Scalability Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                Scalability Strategy                          │
└─────────────────────────────────────────────────────────────┘

Application Layer
├─ Horizontal Scaling (Multiple Servers)
├─ Load Balancer (Nginx/HAProxy)
└─ Stateless Design (API)

Database Layer
├─ Read Replicas
├─ Query Optimization
├─ Proper Indexing
└─ Connection Pooling

Cache Layer
├─ Redis (Sessions, Cache)
├─ Query Result Caching
└─ API Response Caching

Queue Layer
├─ Redis Queue
├─ Multiple Workers
└─ Job Prioritization

File Storage
├─ Cloud Storage (S3/DO Spaces)
├─ CDN Integration
└─ Image Optimization

Monitoring
├─ Application Performance Monitoring
├─ Error Tracking (Sentry)
├─ Log Aggregation
└─ Uptime Monitoring
```

---

## 🔄 Background Jobs Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                  Queue System                                │
└─────────────────────────────────────────────────────────────┘

Jobs:
├─ AssignCaptainToOrder
│  ├─ Triggered: When captain becomes available
│  ├─ Priority: High
│  └─ Retry: 3 times
│
├─ MakeCaptainAvailableJob
│  ├─ Triggered: After service duration
│  ├─ Delayed: Service duration minutes
│  └─ Retry: 3 times
│
└─ SendPushNotification
   ├─ Triggered: Various events
   ├─ Priority: High
   └─ Retry: 2 times

Commands (Cron):
├─ orders:process-unassigned
│  └─ Schedule: Every 5 minutes
│
└─ packages:notify-expired
   └─ Schedule: Daily at 09:00
```

---

This architecture provides a solid foundation for a scalable, maintainable, and professional booking system.
