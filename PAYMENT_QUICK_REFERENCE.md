# Payment System Quick Reference

## API Endpoints

### Subscribe to Package
```http
POST /api/v1/subscribe
Authorization: Bearer {token}
Content-Type: application/json

{
  "package_id": 1,
  "payment_method": "creditcard"  // or "mada", "applepay"
}

Response:
{
  "status": 200,
  "message": "success",
  "data": "http://127.0.0.1:8001/payment-package/1/creditcard?ref=uuid-here"
}
```

### Renew Package
```http
POST /api/v1/renewal-subscribe
Authorization: Bearer {token}
Content-Type: application/json

{
  "package_id": 1,
  "payment_method": "creditcard"
}

Response:
{
  "status": 200,
  "message": "success",
  "data": "http://127.0.0.1:8001/payment-renewal-subscribe/1/creditcard?ref=uuid-here"
}
```

## Payment Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    SUBSCRIBE FLOW                            │
└─────────────────────────────────────────────────────────────┘

1. POST /api/v1/subscribe
   ↓
2. Create UserPackage (status='inactive', reference=UUID)
   ↓
3. Return payment link
   ↓
4. User pays at gateway
   ↓
5. GET /payment-package/{id}/payment/callback?id={payment_id}
   ↓
6. Verify payment with Moyasar
   ↓
7. Check for duplicate payment_id
   ↓
8. Update UserPackage (status='active')
   ↓
9. Create Payment record
   ↓
10. Return success


┌─────────────────────────────────────────────────────────────┐
│                  RENEWAL FLOW                                │
└─────────────────────────────────────────────────────────────┘

1. POST /api/v1/renewal-subscribe
   ↓
2. Validate existing package
   ↓
3. Create new UserPackage (status='inactive', reference=UUID)
   ↓
4. Return payment link
   ↓
5. User pays at gateway
   ↓
6. GET /payment-renewal-subscribe/{id}/payment/callback?id={payment_id}
   ↓
7. Verify payment with Moyasar
   ↓
8. Check for duplicate payment_id
   ↓
9. Cancel old packages (status='canceled')
   ↓
10. Update new UserPackage (status='active')
    ↓
11. Create Payment record
    ↓
12. Return success
```

## Database Tables

### user_packages
```sql
id, user_id, package_id, remaining_washes, 
start_date, expiry_date, status, reference
```

**Status Values:**
- `inactive` - Created but not paid
- `active` - Paid and currently active
- `used_up` - All washes consumed
- `expired` - Past expiry date
- `canceled` - Manually canceled or replaced
- `payment_failed` - Payment failed at gateway

### payments
```sql
id, user_id, user_name, order_number, package_reference,
payment_id, reference, amount, cur, source, status,
description, created_at, updated_at
```

**Status Values:**
- `paid` - Successfully paid
- `failed` - Payment failed
- `pending` - Awaiting payment

## Key Functions

### PaymentService::processPayment()
```php
// Returns: 'already_paid', 'updated', or 'created'
$result = $paymentService->processPayment([
    'user_id' => 1,
    'user_name' => 'John Doe',
    'order_number' => null,
    'package_reference' => 'uuid-here',
    'status' => 'paid',
    'source' => 'visa',
    'payment_id' => 'pay_xxx',
    'cur' => 'SAR',
    'amount' => 100.00,
    'description' => 'Package subscription'
]);
```

## Error Handling

### Duplicate Payment
```php
// If payment_id already exists and status is 'paid'
return 'already_paid';

// If payment_id exists but not paid
// Updates existing record
return 'updated';
```

### Failed Payment
```php
// Updates UserPackage status
$userPackage->update(['status' => 'payment_failed']);

// Returns error response
return ApiResponse::sendResponse(400, 'failed');
```

### Unique Constraint Violation
```php
try {
    Payment::create([...]);
} catch (QueryException $e) {
    if ($e->getCode() === '23000') {
        // Handle duplicate gracefully
        Log::error('Duplicate payment attempt');
    }
}
```

## Common Issues & Solutions

### Issue: "Duplicate entry for key 'payments_reference_unique'"
**Solution:** ✅ Fixed - Reference is now properly generated and nullable

### Issue: "Column 'user_name' doesn't exist"
**Solution:** ✅ Fixed - Migration added all missing columns

### Issue: Payment processed twice
**Solution:** ✅ Fixed - Checks for existing payment_id before creating

### Issue: Empty reference field
**Solution:** ✅ Fixed - Generates UUID if reference not provided

## Testing Commands

```bash
# Check for duplicate payments
mysql -u root -p car-cleaner -e "
  SELECT payment_id, COUNT(*) as count 
  FROM payments 
  GROUP BY payment_id 
  HAVING count > 1;
"

# Check payment statuses
mysql -u root -p car-cleaner -e "
  SELECT status, COUNT(*) 
  FROM payments 
  GROUP BY status;
"

# Check user package statuses
mysql -u root -p car-cleaner -e "
  SELECT status, COUNT(*) 
  FROM user_packages 
  GROUP BY status;
"

# View recent payments
mysql -u root -p car-cleaner -e "
  SELECT id, user_id, payment_id, status, amount, created_at 
  FROM payments 
  ORDER BY id DESC 
  LIMIT 10;
"
```

## Logs to Monitor

```bash
# Watch Laravel logs
tail -f storage/logs/laravel.log | grep -i payment

# Look for these messages:
# - "Creating new payment"
# - "Payment created successfully"
# - "Payment updated"
# - "Duplicate payment attempt"
# - "cancel exist package"
```

## Configuration

### Moyasar Settings
Located in `settings` table:
- `publishable_key` - Public API key
- `secret_key` - Secret API key

### Payment Methods
- `creditcard` - Visa, Mastercard
- `mada` - Mada cards
- `applepay` - Apple Pay (supports all)

## Important Notes

1. **Idempotency**: Multiple callback requests with same `payment_id` are safe
2. **References**: Always unique UUID, never empty
3. **Status Tracking**: UserPackage status reflects payment state
4. **Logging**: All payment operations are logged
5. **Error Handling**: Graceful handling of duplicates and failures

## Files Modified

- ✅ `app/Http/Services/Payment/PaymentService.php`
- ✅ `app/Models/Payment.php`
- ✅ `app/Http/Controllers/Api/PaymentController.php`
- ✅ `database/migrations/2026_05_03_173026_update_payments_table_structure.php`

## Migration Status

```bash
php artisan migrate:status | grep payments
```

Should show:
```
✅ 2024_01_01_000070_create_payments_table
✅ 2026_05_03_173026_update_payments_table_structure
```
