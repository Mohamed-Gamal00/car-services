# Admin Dashboard Quick Reference

## 🎯 Quick Clean - Admin Panel Reference Card

---

## 🔐 LOGIN

**URL**: `/admin/login`  
**Default Credentials**:
- Email: `admin@quickclean.com`
- Password: `password`

⚠️ **Change password immediately after first login!**

---

## 📊 DASHBOARD

### Main Statistics
- Today's orders
- Total revenue
- Active captains
- Pending orders
- Completed orders
- Active packages

### Quick Actions
- View pending orders
- Assign captains
- Send notifications
- View payments

---

## 🛠️ SERVICES MANAGEMENT

### Services (`/dashboard/services`)
**Purpose**: Manage car cleaning services

**Fields**:
- Name (Arabic & English)
- Description (Arabic & English)
- Price (SAR)
- Duration (HH:MM)
- Image
- Icon
- Active status
- Sort order

**Actions**:
- Create new service
- Edit service
- Delete service (soft delete)
- Restore deleted service
- Permanently delete

**Example Services**:
- Exterior Wash - 50 SAR - 30 min
- Interior Cleaning - 70 SAR - 45 min
- Full Service - 100 SAR - 60 min
- Premium Detailing - 200 SAR - 120 min

---

## 📦 PACKAGES MANAGEMENT

### Packages (`/dashboard/packages`)
**Purpose**: Manage subscription packages

**Fields**:
- Name (Arabic & English)
- Description
- Number of washes
- Price (SAR)
- Validity period (days)
- Service duration per wash
- Features list
- Image & Icon
- Active status

**Actions**:
- Create package
- Edit package
- Delete package
- Add/edit features

**Example Packages**:
- Basic: 5 washes, 30 days, 100 SAR
- Premium: 10 washes, 60 days, 180 SAR
- VIP: 15 washes, 90 days, 250 SAR

---

## ➕ ADDITIONAL SERVICES

### Choices (`/dashboard/choices`)
**Purpose**: Manage optional add-ons

**Fields**:
- Name (Arabic & English)
- Price (SAR)
- Description
- Active status

**Examples**:
- Engine Cleaning - 30 SAR
- Wax Polish - 40 SAR
- Tire Shine - 20 SAR
- Air Freshener - 15 SAR

---

## 📋 ORDERS MANAGEMENT

### Orders (`/dashboard/orders`)
**Purpose**: Manage all service bookings

**Order Information**:
- Order number (QC202401010001)
- Customer details
- Service/Package
- Car details
- Location (GPS)
- Booking date & time
- Captain assigned
- Status
- Payment status
- Total price

**Actions**:
- View order details
- Update order status
- Assign/reassign captain
- Cancel order
- View order history
- Download invoice

**Order Statuses**:
1. Pending - Awaiting captain
2. Confirmed - Admin confirmed
3. Assigned - Captain assigned
4. On the way - Captain traveling
5. Captain arrived - At location
6. In progress - Service ongoing
7. Completed - Service done
8. Cancelled - Order cancelled

**Filters**:
- By status
- By date
- By customer
- By captain
- By payment status

---

## 💳 PAYMENTS

### Payments (`/dashboard/payments`)
**Purpose**: View payment transactions

**Information**:
- Payment ID
- Order number
- Customer name
- Amount (SAR)
- Payment method
- Status (paid/failed)
- Date & time

**Payment Methods**:
- Credit Card (Visa, Mastercard)
- Mada
- Apple Pay
- STC Pay

---

## 👥 USERS MANAGEMENT

### Customers (`/dashboard/clients`)
**Purpose**: Manage customer accounts

**Information**:
- Name
- Email
- Phone
- Registration date
- Total orders
- Total spent
- Status (active/inactive)

**Actions**:
- View customer details
- View order history
- Edit customer info
- Change password
- Activate/deactivate account
- Export to Excel

---

### Captains (`/dashboard/captains`)
**Purpose**: Manage service providers

**Information**:
- Name
- Phone
- Status (available/busy)
- Active status
- Total orders completed
- Average rating
- Total earnings

**Actions**:
- View captain details
- View completed orders
- View ratings & reviews
- Edit captain info
- Change password
- Activate/deactivate
- Export to Excel

**Captain Statuses**:
- Available - Ready for orders
- Busy - Currently on order
- Inactive - Not accepting orders

---

### Admins (`/dashboard/admins`)
**Purpose**: Manage admin users

**Information**:
- Name
- Email
- Role
- Permissions
- Last login

**Actions**:
- Create admin
- Edit admin
- Change password
- Delete admin
- Assign permissions

---

## 💰 DISCOUNT CODES

### Discount Codes (`/dashboard/discount_code`)
**Purpose**: Manage promotional codes

**Fields**:
- Code (e.g., SUMMER20)
- Name
- Description
- Type (percentage/fixed)
- Value
- Minimum order amount
- Maximum discount amount
- Usage limit (total)
- Usage limit per user
- Start date
- Expiry date
- Applicable services
- Status

**Actions**:
- Create discount code
- Edit discount code
- Delete discount code
- View usage statistics
- Export to Excel

**Examples**:
- WELCOME10 - 10% off first order
- SUMMER20 - 20 SAR off orders above 100 SAR
- VIP30 - 30% off (max 50 SAR)

---

## 🌍 LOCATIONS

### Countries (`/dashboard/countries`)
- Manage countries
- Add/edit/delete

### Cities (`/dashboard/cities`)
- Manage service areas
- Link to countries
- Add/edit/delete

### Cars (`/dashboard/cars`)
- Manage car brands & models
- Add/edit/delete
- Used for customer vehicle selection

**Example Cars**:
- Toyota Camry
- Honda Accord
- BMW 5 Series
- Mercedes C-Class

---

## 📄 CONTENT MANAGEMENT

### Static Pages (`/dashboard/pages`)
**Purpose**: Manage app content pages

**Pages**:
- About Us
- Terms & Conditions
- Privacy Policy
- How It Works

**Actions**:
- Edit page content
- Update images
- Publish/unpublish

---

### FAQ (`/dashboard/common_questions`)
**Purpose**: Manage frequently asked questions

**Fields**:
- Question (Arabic & English)
- Answer (Arabic & English)
- Sort order
- Active status

**Actions**:
- Add question
- Edit question
- Delete question
- Reorder questions

---

### Contact Messages (`/dashboard/contact_us_view`)
**Purpose**: View customer inquiries

**Information**:
- Customer name
- Email
- Phone
- Message
- Date
- Status (read/unread)

**Actions**:
- View message
- Mark as read
- Delete message

---

## ⚙️ SETTINGS

### System Settings (`/dashboard/settings`)
**Purpose**: Configure system

**Settings Groups**:

**Working Hours**:
- Start time (e.g., 08:00)
- End time (e.g., 22:00)

**Booking Rules**:
- Minimum advance booking (30 minutes)
- Maximum advance booking (30 days)

**Payment Gateway** (Moyasar):
- API Key
- Secret Key
- Publishable Key

**Firebase**:
- Server Key (for push notifications)

**Contact Information**:
- Phone
- Email
- Address
- Social media links

**App Settings**:
- App name
- Currency (SAR)
- Tax rate (15%)
- Default language

---

## 🔔 PUSH NOTIFICATIONS

### Send Notification (`/dashboard/push_notification`)
**Purpose**: Send push notifications to users

**Options**:
- Send to all users
- Send to all captains
- Send to specific user
- Send to specific captain

**Fields**:
- Title
- Message
- Target audience

**Use Cases**:
- Announce promotions
- System maintenance notice
- New feature announcement
- Special offers

---

## 📊 REPORTS & ANALYTICS

### Reports (`/dashboard/reports`)
**Purpose**: Generate business reports

**Available Reports**:

**Revenue Report**:
- Total revenue
- Revenue by date range
- Revenue by service
- Revenue by package

**Orders Report**:
- Total orders
- Orders by status
- Orders by date
- Orders by captain
- Completion rate

**Customer Report**:
- Total customers
- New registrations
- Active customers
- Customer lifetime value

**Captain Report**:
- Total captains
- Active captains
- Orders per captain
- Average rating
- Earnings per captain

**Service Report**:
- Most popular services
- Service revenue
- Service bookings

**Package Report**:
- Active subscriptions
- Package revenue
- Most popular packages

**Export Formats**:
- Excel (.xlsx)
- PDF
- CSV

---

## 🔧 COMMON TASKS

### Assign Captain to Order
1. Go to Orders
2. Click on pending order
3. Select available captain
4. Click "Assign Captain"
5. Captain receives notification

### Create Discount Code
1. Go to Discount Codes
2. Click "Create New"
3. Enter code details
4. Set usage limits
5. Select applicable services
6. Save

### Add New Service
1. Go to Services
2. Click "Create New"
3. Fill in details (Arabic & English)
4. Upload image
5. Set price and duration
6. Save

### View Order Details
1. Go to Orders
2. Click on order number
3. View full details
4. See customer info
5. Check captain status
6. View payment info

### Generate Report
1. Go to Reports
2. Select report type
3. Choose date range
4. Apply filters
5. Click "Generate"
6. Export if needed

---

## 🚨 TROUBLESHOOTING

### Captain Not Receiving Orders
1. Check captain status (must be "available")
2. Check captain is_active (must be true)
3. Verify device token registered
4. Check Firebase settings
5. Test notification manually

### Order Not Assigned
1. Check if captains available
2. Verify order is paid
3. Check queue worker running
4. Review error logs
5. Manually assign captain

### Payment Not Processing
1. Verify Moyasar credentials
2. Check payment gateway status
3. Review payment logs
4. Test with test mode
5. Contact Moyasar support

### Notification Not Sending
1. Check Firebase server key
2. Verify device tokens
3. Test notification
4. Check Firebase console
5. Review error logs

---

## 📱 MOBILE APP SYNC

### Data Synced to Mobile Apps:
- Services list
- Packages list
- Order statuses
- Discount codes
- Settings
- Static pages
- FAQ
- Cities & cars

### Real-time Updates:
- Order status changes
- Captain assignment
- Payment confirmation
- Service completion
- Push notifications

---

## 🔒 SECURITY

### Best Practices:
- Change default password
- Use strong passwords
- Don't share admin credentials
- Log out when done
- Review admin activity logs
- Keep system updated

### Password Requirements:
- Minimum 8 characters
- Mix of letters and numbers
- Include special characters
- Change regularly

---

## 📞 SUPPORT

### Technical Issues:
- Check error logs: `storage/logs/laravel.log`
- Clear cache: Settings → Clear Cache
- Contact developer

### Business Questions:
- Review documentation
- Check FAQ
- Contact support team

---

## ⌨️ KEYBOARD SHORTCUTS

- `Ctrl + S` - Save form
- `Ctrl + F` - Search
- `Esc` - Close modal
- `Alt + N` - New item (on list pages)

---

## 📚 QUICK LINKS

- Dashboard: `/dashboard`
- Orders: `/dashboard/orders`
- Services: `/dashboard/services`
- Packages: `/dashboard/packages`
- Captains: `/dashboard/captains`
- Customers: `/dashboard/clients`
- Reports: `/dashboard/reports`
- Settings: `/dashboard/settings`

---

**Quick Clean Admin Panel**  
**Version**: 1.0  
**Last Updated**: January 2024

---

*For detailed documentation, see PROJECT_DOCUMENTATION.md*
