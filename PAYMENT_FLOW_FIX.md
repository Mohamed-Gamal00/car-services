# Payment Flow Fix - Duplicate Entry Issue

## Problem Summary

When users tried to pay for the same package twice, the system threw:
```
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '' for key 'payments.payments_reference_unique'
```

## Root Causes

1. **Empty Reference Field**: The `reference` column in payments table had a unique constraint, but was being set to empty string `''` instead of a proper value
2. **Missing Columns**: The payments table was missing several columns that the code was trying to use (`user_name`, `order_number`, `package_reference`, `source`, `cur`, `description`)
3. **No Duplicate Handling**: `PaymentService.processPayment()` used `create()` without checking for existing payments properly
4. **Model Mismatch**: The `Payment` model's fillable array didn't include all the fields being used

## Payment Flow

### Subscribe Flow
```
User → PackageController.subscribe()
  ↓
Creates UserPackage with status='inactive' and unique reference
  ↓
Returns payment link with reference
  ↓
User completes payment → PaymentController.package_callback()
  ↓
Verifies payment with Moyasar API
  ↓
Updates UserPackage to status='active'
  ↓
PaymentService.processPayment() creates Payment record
```

### Renewal Subscribe Flow
```
User → PackageController.RenewalSubscribe()
  ↓
Checks for existing active/used_up/expired package
  ↓
Creates new UserPackage with status='inactive' and unique reference
  ↓
Returns payment link with reference
  ↓
User completes payment → PaymentController.renewal_package_callback()
  ↓
Verifies payment with Moyasar API
  ↓
Cancels old packages (active/used_up/expired)
  ↓
Updates new UserPackage to status='active'
  ↓
PaymentService.processPayment() creates Payment record
```

## Solutions Implemented

### 1. Updated Payment Model
**File**: `app/Models/Payment.php`

Added missing fields to fillable array:
- `user_name`
- `order_number`
- `package_reference`
- `source`
- `cur`
- `description`

### 2. Fixed PaymentService
**File**: `app/Http/Services/Payment/PaymentService.php`

**Changes**:
- Check for existing payment by `payment_id` first
- If exists and paid, return `'already_paid'`
- If exists but not paid, update the record instead of creating new one
- Generate proper `reference` value (never empty string):
  - Use `package_reference` if available
  - Use `order_number` if available
  - Generate UUID as fallback
- Added try-catch for `QueryException` to handle unique constraint violations gracefully
- Added detailed logging for debugging

### 3. Database Migration
**File**: `database/migrations/2026_05_03_173026_update_payments_table_structure.php`

**Changes**:
- Added missing columns: `user_name`, `order_number`, `package_reference`, `source`, `cur`, `description`
- Made `reference` column nullable to prevent empty string issues
- Updated existing empty string references to `null`

### 4. Updated PaymentController
**File**: `app/Http/Controllers/Api/PaymentController.php`

**Changes in `package_callback()`**:
- Check payment status properly before processing
- Handle `already_paid` response from PaymentService
- Update UserPackage status to `payment_failed` if payment fails

**Changes in `renewal_package_callback()`**:
- Check payment status properly before processing
- Handle `already_paid` response from PaymentService
- Update UserPackage status to `payment_failed` if payment fails

## Testing the Fix

### Test Case 1: First Payment
```bash
# Subscribe to package
POST /api/v1/subscribe
{
  "package_id": 1,
  "payment_method": "creditcard"
}

# Complete payment (should succeed)
# Visit returned payment link and complete payment
```

### Test Case 2: Duplicate Payment Attempt
```bash
# Try to pay for same package again
# The system should:
# 1. Detect existing payment by payment_id
# 2. Return "already_paid" message
# 3. Not create duplicate payment record
```

### Test Case 3: Renewal Payment
```bash
# Renew existing package
POST /api/v1/renewal-subscribe
{
  "package_id": 1,
  "payment_method": "creditcard"
}

# Complete payment (should succeed)
# Old package should be canceled
# New package should be activated
```

### Test Case 4: Failed Payment
```bash
# If payment fails at gateway:
# 1. UserPackage status should be set to 'payment_failed'
# 2. No Payment record should be created
# 3. User can retry payment
```

## Database Schema

### Payments Table Structure
```sql
CREATE TABLE payments (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  user_name VARCHAR(255) NULL,
  order_number VARCHAR(255) NULL,
  package_reference VARCHAR(255) NULL,
  payment_id VARCHAR(255) UNIQUE NOT NULL,
  reference VARCHAR(255) NULL,
  amount DECIMAL(10,2) NOT NULL,
  cur VARCHAR(3) DEFAULT 'SAR',
  currency VARCHAR(3) DEFAULT 'SAR',
  method ENUM(...) DEFAULT 'credit_card',
  source VARCHAR(255) NULL,
  status ENUM(...) DEFAULT 'pending',
  gateway_response JSON NULL,
  description TEXT NULL,
  paid_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_status (user_id, status)
);
```

## Key Points

1. **Unique Constraint**: `payment_id` is unique (from payment gateway)
2. **Reference Field**: Now nullable, used for internal tracking
3. **Idempotency**: Multiple callback requests with same `payment_id` won't create duplicates
4. **Error Handling**: Graceful handling of unique constraint violations
5. **Status Tracking**: Failed payments are tracked in UserPackage status

## Error Prevention

The fix prevents these errors:
- ✅ Duplicate entry for `payment_id`
- ✅ Duplicate entry for `reference`
- ✅ Missing column errors
- ✅ Empty string in unique column
- ✅ Unhandled QueryException

## Monitoring

Check logs for these messages:
- `"Creating new payment"` - New payment being created
- `"Payment created successfully"` - Payment created
- `"Payment updated"` - Existing payment updated
- `"Duplicate payment attempt"` - Duplicate detected and handled
- `"cancel exist package"` - Old package being canceled (renewal flow)

## Future Improvements

1. Add transaction wrapping for payment + package update
2. Add payment retry mechanism with exponential backoff
3. Add webhook handler for async payment notifications
4. Add payment reconciliation job
5. Add payment status transition validation
6. Consider adding payment_attempts counter
