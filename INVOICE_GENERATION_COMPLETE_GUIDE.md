# Invoice Generation Complete Guide

## Overview

Complete guide for debugging and testing invoice generation from checkout to invoice creation.

## Complete Flow

### 1. Checkout → Order Creation
```
POST /checkout/{service_id}
↓
CheckoutController::usercheckout()
↓
CheckoutService::createOrderFromService()
↓
Order created (NO invoice yet for service orders)
↓
Return payment URL
```

### 2. Payment Page
```
GET /payment-page/{order_number}/{method}
↓
PaymentControllerRefactored::index()
↓
Display Moyasar payment form
↓
User completes payment
```

### 3. Payment Callback → Invoice Generation
```
GET /payment-page/{number}/payment/callback?id={payment_id}
↓
PaymentControllerRefactored::callback()
↓
PaymentCallbackService::processCallback()
↓
OrderPaymentService::getPaymentDetails() (from Moyasar)
↓
PaymentService::processPayment() (save payment record)
↓
OrderPaymentService::updateOrderAfterPayment() (mark as paid)
↓
CaptainAssignmentService::assignCaptainIfToday()
↓
InvoiceGenerationService::generateInvoice() ← INVOICE CREATED HERE
↓
Helper::generateInvoicePDF()
↓
Return success view
```

## Error Handling (Triple Layer)

### Layer 1: Helper Method (Most Detailed)
**File:** `app/Helper/Helper.php`

```php
public function generateInvoicePDF($order_details)
{
    try {
        // Comprehensive logging at each step
        Log::info('Starting invoice generation');
        
        // Load and verify all relationships
        // Build data array with null checks
        // Render Blade template
        // Generate PDF with MPDF
        // Save to storage
        
        return $filePath;
    } catch (\Exception $e) {
        Log::error('Invoice generation failed', [
            'order_id' => $order_details->id,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        return null; // Never throw
    }
}
```

### Layer 2: Invoice Service
**File:** `app/Http/Services/Payment/InvoiceGenerationService.php`

```php
public function generateInvoice(Order $order): ?string
{
    try {
        // Load missing relationships
        // Call Helper method
        // Update order with invoice URL
        
        return $invoicePath;
    } catch (\Exception $e) {
        Log::error('Invoice service failed');
        return null; // Never throw
    }
}
```

### Layer 3: Callback Service (Final Safety Net)
**File:** `app/Http/Services/Payment/PaymentCallbackService.php`

```php
protected function handleSuccessfulPayment(Order $order, array $payment): array
{
    // Update order, assign captain
    
    try {
        // Refresh and load all relationships
        $order->refresh();
        $order->load(['user', 'car', 'choices', 'service', 'userPackage.package']);
        
        // Generate invoice
        $this->invoiceGenerationService->generateInvoice($order);
    } catch (\Throwable $e) {
        Log::error('Invoice failed in callback');
        // Payment still completes!
    }
    
    return ['status' => 'success'];
}
```

## Debugging Tools

### 1. Artisan Command (Recommended)

```bash
# Regenerate invoice for specific order
php artisan invoice:regenerate ORDER_NUMBER

# Example
php artisan invoice:regenerate ORD-2024-001
```

**Output:**
```
Looking for order: ORD-2024-001
Order found: ID 123
Current invoice URL: None

+---------------+----------+
| Relationship  | Status   |
+---------------+----------+
| User          | ✓ Loaded |
| Car           | ✓ Loaded |
| Service       | ✓ Loaded |
| Package       | ✗ Missing|
| Choices       | 2 items  |
+---------------+----------+

Do you want to regenerate the invoice? (yes/no) [yes]:
> yes

Generating invoice...
✓ Invoice generated successfully!
Invoice path: invoices/invoice_1234567890_abc12.pdf
Full URL: http://yoursite.com/storage/invoices/invoice_1234567890_abc12.pdf
```

### 2. Watch Logs

```bash
# All invoice logs
tail -f storage/logs/laravel.log | grep -i invoice

# Payment flow
tail -f storage/logs/laravel.log | grep -i payment

# Errors only
tail -f storage/logs/laravel.log | grep -i error

# Specific order
tail -f storage/logs/laravel.log | grep "order_id.*123"
```

### 3. Telescope

1. Navigate to `/telescope`
2. Click "Requests" tab
3. Find payment callback request
4. Check:
   - Request parameters
   - Response status
   - Exceptions
   - Database queries
   - Log entries

### 4. Tinker Testing

```php
php artisan tinker

// Load order with all relationships
$order = Order::with(['user', 'car', 'service', 'choices', 'userPackage.package'])
    ->where('number', 'ORDER_NUMBER')
    ->first();

// Verify relationships
$order->user;          // Should not be null
$order->service;       // Should not be null (for service orders)
$order->car;           // May be null
$order->choices;       // Collection (may be empty)

// Test invoice generation
$service = app(\App\Http\Services\Payment\InvoiceGenerationService::class);
$result = $service->generateInvoice($order);

if ($result) {
    echo "✓ Invoice: " . asset('storage/' . $result) . "\n";
} else {
    echo "✗ Failed - check logs\n";
}
```

## Required Data

### Order Fields (Always Present)
- `id` - Order ID
- `number` - Order number
- `booking_date` - Booking date
- `booking_time` - Booking time
- `total_price` - Total price
- `totalBeforeDiscount` - Price before discount
- `car_model` - Car model
- `car_number` - Car number
- `payment_status` - Default: 'pending'
- `payment_method` - Default: 'N/A'
- `discount_applied` - Default: null

### User Relationship (Required)
- `first_name` - With fallback to 'N/A'
- `family_name` - With fallback to 'N/A'
- `phone_number` - Default: 'N/A'

### Car Relationship (Optional)
- `getCurrentNameAttribute()` - Default: 'N/A'

### Service Relationship (Required for service orders)
- `getCurrentNameAttribute()` - Service name
- `duration` - Service duration
- `price` - Service price

### UserPackage Relationship (Required for package orders)
- `package.getCurrentNameAttribute()` - Package name
- `package.duration` - Package duration
- `package.price` - Package price

### Choices Relationship (Optional)
- Collection of choices (may be empty)
- Each choice:
  - `getCurrentNameLangAttribute()` - Choice name
  - `service_price` - Choice price

## Common Issues & Solutions

### Issue 1: DOMDocument Empty HTML
**Error:** `DOMDocument::loadHTML(): Argument #1 ($source) must not be empty`

**Causes:**
- View rendering failed
- Missing required data
- Template file not found

**Solutions:**
✓ Check logs for view rendering errors
✓ Verify template exists: `resources/views/invoice/invoice.blade.php`
✓ Ensure all data fields have default values
✓ Test view rendering separately

### Issue 2: Null Relationship
**Error:** `Call to a member function X on null`

**Causes:**
- Relationship not loaded
- Related record doesn't exist in database

**Solutions:**
✓ Eager load all relationships before invoice generation
✓ Use `optional()` helper for nullable relationships
✓ Provide default values for missing data
✓ Check database for missing records

### Issue 3: Payment Blocked
**Issue:** Payment doesn't complete when invoice fails

**Solution:**
✓ Invoice generation wrapped in try-catch (3 layers)
✓ Payment completes even if invoice fails
✓ Invoice can be regenerated later
✓ Detailed error logging for debugging

### Issue 4: Missing Service Data
**Error:** Service name/duration/price is null

**Causes:**
- Service relationship not loaded
- Package relationship not loaded (for package orders)

**Solutions:**
✓ Load both `service` and `userPackage.package` relationships
✓ Check both relationships with fallback logic
✓ Provide default values ('N/A', 0)

## Testing Checklist

Test invoice generation with:
- [ ] Service order without choices
- [ ] Service order with multiple choices
- [ ] Service order without car relationship
- [ ] Package order
- [ ] Order with discount code applied
- [ ] Order with missing optional data
- [ ] Order immediately after payment
- [ ] Order regeneration via command

## Log Messages

### Success Flow
```
[INFO] Starting invoice generation (order_id: 123)
[INFO] Loading user relationship
[INFO] Loading car relationship
[INFO] Loading service relationship
[INFO] Relationships loaded for invoice (has_user: true, has_service: true)
[INFO] Invoice data prepared (choices_count: 2)
[INFO] Invoice HTML generated (html_length: 5432)
[INFO] Invoice PDF generated successfully (file_path: invoices/invoice_xxx.pdf)
[INFO] Invoice generated and saved to order
```

### Warning Messages
```
[WARNING] Failed to get car name (error: ...)
[WARNING] Failed to get service details (error: ...)
[WARNING] Invoice generation returned null from Helper
```

### Error Messages
```
[ERROR] User relationship is null (order_id: 123)
[ERROR] Both service and userPackage relationships are null
[ERROR] Failed to render invoice view (error: ...)
[ERROR] Invoice HTML is empty after rendering
[ERROR] Invoice generation failed in Helper
```

## Recovery & Prevention

### If Invoice Fails During Payment

1. ✓ Payment completes successfully (by design)
2. ✓ Error logged with full details
3. ✓ Regenerate using artisan command:
   ```bash
   php artisan invoice:regenerate ORDER_NUMBER
   ```
4. ✓ Check logs for root cause
5. ✓ Fix underlying issue
6. ✓ Regenerate for affected orders

### Prevention Best Practices

1. **Validate at Checkout**
   - Ensure all required fields present
   - Verify relationships exist

2. **Eager Load Relationships**
   - Load all needed relationships before invoice
   - Use `with()` or `load()` methods

3. **Use Default Values**
   - Provide sensible defaults for optional fields
   - Never pass null to template

4. **Comprehensive Logging**
   - Log each step of invoice generation
   - Include relationship status
   - Log data being passed to template

5. **Monitor & Alert**
   - Set up alerts for invoice failures
   - Review logs regularly
   - Track success rate

## File Permissions

Ensure storage directories are writable:

```bash
# Check permissions
ls -la storage/app/public/invoices

# Fix if needed
chmod -R 775 storage
chown -R www-data:www-data storage

# Create invoices directory if missing
mkdir -p storage/app/public/invoices
```

## Support Checklist

If issues persist, check:
- [ ] Laravel logs: `storage/logs/laravel.log`
- [ ] Telescope: `/telescope`
- [ ] Database relationships exist
- [ ] Template file exists and renders
- [ ] Storage directory writable
- [ ] All required packages installed (mpdf)
- [ ] PHP memory limit sufficient
- [ ] Test invoice generation in isolation

## Quick Reference

```bash
# Regenerate invoice
php artisan invoice:regenerate ORDER_NUMBER

# Watch logs
tail -f storage/logs/laravel.log | grep invoice

# Test in tinker
php artisan tinker
$order = Order::with(['user','car','service','choices'])->find(ID);
app(\App\Http\Services\Payment\InvoiceGenerationService::class)->generateInvoice($order);

# Check Telescope
# Navigate to: /telescope

# Fix permissions
chmod -R 775 storage
```
