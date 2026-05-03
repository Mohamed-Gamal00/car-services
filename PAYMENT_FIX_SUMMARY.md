# Payment Duplicate Entry Fix - Summary

## Issue
```
SQLSTATE[23000]: Integrity constraint violation: 1062 
Duplicate entry '' for key 'payments.payments_reference_unique'
```

This error occurred when users tried to pay for the same package multiple times.

## Root Cause
1. Empty `reference` field with unique constraint
2. Missing database columns that code was trying to use
3. No proper duplicate payment handling
4. Model fillable array didn't match actual usage

## Files Modified

### 1. `app/Http/Services/Payment/PaymentService.php`
**Changes:**
- Added check for existing payment by `payment_id`
- Update existing payment instead of creating duplicate
- Generate proper `reference` value (never empty)
- Added exception handling for unique constraint violations
- Added detailed logging

**Key Logic:**
```php
// Check if payment exists
$existingPayment = Payment::where('payment_id', $paymentData['payment_id'])->first();

if ($existingPayment) {
    if ($existingPayment->status === 'paid') {
        return 'already_paid';
    }
    // Update instead of create
    $existingPayment->update([...]);
    return 'updated';
}

// Generate proper reference (never empty)
$reference = $paymentData['package_reference'] 
    ?? $paymentData['order_number'] 
    ?? Str::uuid()->toString();

// Create with try-catch
try {
    Payment::create([...]);
} catch (QueryException $e) {
    // Handle duplicate gracefully
}
```

### 2. `app/Models/Payment.php`
**Changes:**
- Added missing fields to `$fillable` array:
  - `user_name`
  - `order_number`
  - `package_reference`
  - `source`
  - `cur`
  - `description`

### 3. `app/Http/Controllers/Api/PaymentController.php`
**Changes in `package_callback()` and `renewal_package_callback()`:**
- Check payment status properly: `$existingPayment->status === 'paid'`
- Handle `already_paid` response from PaymentService
- Update UserPackage to `payment_failed` status on failed payments

**Before:**
```php
if ($existingPayment) {
    return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
}
$this->paymentService->processPayment($paymentData);
```

**After:**
```php
if ($existingPayment && $existingPayment->status === 'paid') {
    return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
}
$result = $this->paymentService->processPayment($paymentData);
if ($result === 'already_paid') {
    return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
}
```

### 4. `database/migrations/2026_05_03_173026_update_payments_table_structure.php`
**New Migration:**
- Added missing columns: `user_name`, `order_number`, `package_reference`, `source`, `cur`, `description`
- Made `reference` column nullable
- Updated existing empty references to `null`

## How It Works Now

### Subscribe Flow
1. User calls `/api/v1/subscribe` with package_id
2. System creates `UserPackage` with status='inactive' and unique reference
3. Returns payment link with reference
4. User completes payment at gateway
5. Gateway calls callback with payment_id
6. System checks if payment already processed
7. If new: Creates payment record, activates package
8. If duplicate: Returns "already_paid" message

### Renewal Flow
1. User calls `/api/v1/renewal-subscribe` with package_id
2. System validates existing package
3. Creates new `UserPackage` with status='inactive'
4. Returns payment link
5. User completes payment
6. System cancels old packages
7. Activates new package
8. Creates payment record (with duplicate check)

## Testing

Run the test script:
```bash
./test_payment_flow.sh
```

Or test manually:
```bash
# 1. Login
curl -X POST http://127.0.0.1:8001/api/v1/login \
  -H 'Content-Type: application/json' \
  -d '{"phone":"YOUR_PHONE","password":"YOUR_PASSWORD"}'

# 2. Subscribe
curl -X POST http://127.0.0.1:8001/api/v1/subscribe \
  -H 'Authorization: Bearer YOUR_TOKEN' \
  -H 'Content-Type: application/json' \
  -d '{"package_id":1,"payment_method":"creditcard"}'

# 3. Try to pay twice (should handle gracefully)
```

## Verification

Check database:
```sql
-- No duplicate payment_ids
SELECT payment_id, COUNT(*) 
FROM payments 
GROUP BY payment_id 
HAVING COUNT(*) > 1;

-- No empty references
SELECT COUNT(*) 
FROM payments 
WHERE reference = '' OR reference IS NULL;

-- Check payment statuses
SELECT status, COUNT(*) 
FROM payments 
GROUP BY status;
```

## Benefits

✅ **No More Duplicates**: Prevents duplicate payment records
✅ **Idempotent**: Multiple callback requests handled safely
✅ **Proper References**: Never stores empty strings
✅ **Better Logging**: Detailed logs for debugging
✅ **Failed Payment Tracking**: UserPackage status reflects payment failures
✅ **Graceful Error Handling**: Catches and handles constraint violations

## Migration Applied

```bash
php artisan migrate --path=database/migrations/2026_05_03_173026_update_payments_table_structure.php
```

Status: ✅ **DONE** (80ms)

## Next Steps

1. Test with real payment gateway
2. Monitor logs for any issues
3. Consider adding:
   - Payment retry mechanism
   - Webhook handler for async notifications
   - Payment reconciliation job
   - Transaction wrapping for atomicity

## Related Files

- `PAYMENT_FLOW_FIX.md` - Detailed documentation
- `test_payment_flow.sh` - Test helper script
- `app/Http/Services/Payment/PaymentService.php` - Core payment logic
- `app/Http/Controllers/Api/PaymentController.php` - Payment callbacks
- `app/Http/Controllers/Api/PackageController.php` - Subscribe endpoints
