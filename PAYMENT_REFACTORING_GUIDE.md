# Payment Functionality Refactoring Guide

## 📋 Overview

The payment functionality has been refactored into smaller, more maintainable services following the Single Responsibility Principle (SRP). Each service handles a specific aspect of the payment process.

## 🏗️ Architecture

### Before Refactoring
```
PaymentController
├── index() - 70 lines of mixed logic
└── callback() - 150 lines of mixed logic
```

### After Refactoring
```
PaymentController (Orchestrator)
├── index() - Delegates to PaymentPageService
└── callback() - Delegates to PaymentCallbackService

Services Layer:
├── PaymentPageService - Payment page display logic
├── PaymentCallbackService - Orchestrates callback flow
├── OrderPaymentService - Order payment operations
├── CaptainAssignmentService - Captain assignment logic
└── InvoiceGenerationService - Invoice generation logic
```

## 📁 New Service Files

### 1. **PaymentPageService**
**Location:** `app/Http/Services/Payment/PaymentPageService.php`

**Responsibilities:**
- Get payment networks based on method
- Get Moyasar publishable key
- Validate payment methods
- Prepare payment page data

**Methods:**
```php
getPaymentNetworks(string $method): array
getPublishableKey(): string
isValidPaymentMethod(string $method): bool
preparePaymentPageData(Order $order, string $method): array
```

### 2. **OrderPaymentService**
**Location:** `app/Http/Services/Payment/OrderPaymentService.php`

**Responsibilities:**
- Get payment details from Moyasar API
- Prepare payment data for processing
- Update order after successful payment
- Update order after failed payment
- Check for authentication errors

**Methods:**
```php
getPaymentDetails(string $paymentId): array
preparePaymentData(Order $order, array $payment): array
updateOrderAfterPayment(Order $order, array $payment): void
updateOrderAfterFailedPayment(Order $order): void
isAuthenticationError(array $payment): bool
```

### 3. **CaptainAssignmentService**
**Location:** `app/Http/Services/Payment/CaptainAssignmentService.php`

**Responsibilities:**
- Get available captain
- Assign captain to order if booking is today
- Notify captain
- Dispatch assignment job

**Methods:**
```php
getAvailableCaptain(): ?Captain
assignCaptainIfToday(Order $order): void
```

### 4. **InvoiceGenerationService**
**Location:** `app/Http/Services/Payment/InvoiceGenerationService.php`

**Responsibilities:**
- Generate invoice PDF
- Check if invoice should be generated
- Handle invoice generation errors gracefully

**Methods:**
```php
generateInvoice(Order $order): ?string
shouldGenerateInvoice(Order $order): bool
```

### 5. **PaymentCallbackService**
**Location:** `app/Http/Services/Payment/PaymentCallbackService.php`

**Responsibilities:**
- Orchestrate the entire payment callback flow
- Handle successful payments
- Handle failed payments
- Handle unknown payment statuses

**Methods:**
```php
processCallback(Order $order, string $paymentId): array
handleSuccessfulPayment(Order $order, array $payment): array
handleFailedPayment(Order $order): array
handleUnknownPaymentStatus(Order $order): array
```

## 🔄 Migration Path

### Option 1: Gradual Migration (Recommended)
1. Keep existing `PaymentController` as is
2. Use `PaymentControllerRefactored` for new code
3. Test thoroughly
4. Once confident, replace old controller

### Option 2: Direct Replacement
1. Backup current `PaymentController`
2. Replace with refactored version
3. Update routes if needed
4. Test all payment flows

## 🚀 How to Use

### Update Routes (if using refactored controller)

In `routes/web.php`:

```php
// Old routes (keep for now)
Route::get('/payment-page/{order_number}/{method}', 
    [\App\Http\Controllers\Api\PaymentController::class, 'index'])
    ->name('user.payment');

Route::get('/payment-page/{number}/payment/callback', 
    [\App\Http\Controllers\Api\PaymentController::class, 'callback'])
    ->name('payment.callback');

// New routes (when ready to switch)
Route::get('/payment-page/{order_number}/{method}', 
    [\App\Http\Controllers\Api\PaymentControllerRefactored::class, 'index'])
    ->name('user.payment');

Route::get('/payment-page/{number}/payment/callback', 
    [\App\Http\Controllers\Api\PaymentControllerRefactored::class, 'callback'])
    ->name('payment.callback');
```

### Using Services Directly

You can also use services directly in other parts of your application:

```php
// Example: Generate invoice manually
$invoiceService = app(InvoiceGenerationService::class);
$invoicePath = $invoiceService->generateInvoice($order);

// Example: Assign captain manually
$captainService = app(CaptainAssignmentService::class);
$captainService->assignCaptainIfToday($order);

// Example: Get payment details
$paymentService = app(OrderPaymentService::class);
$paymentDetails = $paymentService->getPaymentDetails($paymentId);
```

## ✅ Benefits of Refactoring

### 1. **Single Responsibility**
Each service has one clear purpose, making code easier to understand and maintain.

### 2. **Testability**
Services can be unit tested independently:
```php
// Test invoice generation without touching payment logic
$service = new InvoiceGenerationService();
$result = $service->generateInvoice($mockOrder);
```

### 3. **Reusability**
Services can be used in multiple places:
- Admin panel for manual invoice generation
- Cron jobs for retry failed invoices
- API endpoints for captain assignment

### 4. **Maintainability**
- Easy to locate bugs (specific service)
- Easy to add features (extend specific service)
- Easy to modify behavior (change one service)

### 5. **Dependency Injection**
Services are injected via constructor, making them:
- Easy to mock in tests
- Easy to swap implementations
- Clear about dependencies

## 🧪 Testing

### Unit Test Example

```php
use Tests\TestCase;
use App\Http\Services\Payment\OrderPaymentService;
use App\Models\Order;

class OrderPaymentServiceTest extends TestCase
{
    public function test_prepare_payment_data()
    {
        $service = new OrderPaymentService();
        $order = Order::factory()->create();
        
        $payment = [
            'status' => 'paid',
            'source' => ['company' => 'visa'],
            'id' => 'pay_123',
            'currency' => 'SAR',
            'amount' => 10000,
        ];
        
        $result = $service->preparePaymentData($order, $payment);
        
        $this->assertEquals('paid', $result['status']);
        $this->assertEquals('visa', $result['source']);
        $this->assertEquals($order->number, $result['order_number']);
    }
}
```

## 📊 Flow Diagram

### Payment Callback Flow

```
User Completes Payment
        ↓
Moyasar Redirects to Callback URL
        ↓
PaymentController::callback()
        ↓
PaymentCallbackService::processCallback()
        ↓
    ┌───┴───┐
    ↓       ↓
OrderPaymentService    PaymentService
(Get payment details)  (Process payment)
        ↓
    ┌───┴───┐
    ↓       ↓
Success?    Failed?
    ↓       ↓
    ↓   Update order as failed
    ↓   Return failure view
    ↓
Update order as paid
    ↓
CaptainAssignmentService
(Assign captain if today)
    ↓
InvoiceGenerationService
(Generate invoice)
    ↓
Return success view
```

## 🔍 Debugging

### Using Telescope

1. Go to `/telescope`
2. Click "Requests" tab
3. Find your payment callback
4. Check:
   - Which services were called
   - What data was passed
   - Any errors that occurred

### Logging

Each service logs its operations:

```php
// Check logs for payment flow
tail -f storage/logs/laravel.log | grep "payment"

// Check logs for invoice generation
tail -f storage/logs/laravel.log | grep "invoice"

// Check logs for captain assignment
tail -f storage/logs/laravel.log | grep "captain"
```

## 🎯 Next Steps

### Phase 1: Testing (Current)
- Test refactored services
- Compare with old implementation
- Fix any issues

### Phase 2: Package Payments
- Refactor package payment methods similarly
- Create `PackagePaymentService`
- Create `PackageCallbackService`

### Phase 3: Cleanup
- Remove old controller
- Update all routes
- Remove unused code

### Phase 4: Enhancement
- Add retry logic for failed invoices
- Add webhook support
- Add payment analytics

## 📚 Additional Resources

- [Laravel Service Container](https://laravel.com/docs/container)
- [Dependency Injection](https://laravel.com/docs/providers)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [Service Layer Pattern](https://martinfowler.com/eaaCatalog/serviceLayer.html)

## 🤝 Contributing

When adding new payment features:

1. Identify which service it belongs to
2. Add method to appropriate service
3. Update service tests
4. Document the change
5. Update this guide

## ⚠️ Important Notes

1. **Don't delete old controller yet** - Keep it as backup
2. **Test thoroughly** - Payment is critical functionality
3. **Monitor logs** - Watch for any issues in production
4. **Gradual rollout** - Test with small percentage of users first

---

**Happy Coding! 🎉**
