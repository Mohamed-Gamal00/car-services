<?php

namespace App\Http\Services\Payment;

use App\Models\Order;
use App\Models\Setting;

class PaymentPageService
{
    public function getPaymentNetworks(string $method): array
    {
        return match ($method) {
            'creditcard' => ['mastercard', 'visa'],
            'mada' => ['mada'],
            'applepay' => ['visa', 'mastercard', 'mada'],
            default => [],
        };
    }

    public function getPublishableKey(): string
    {
        return Setting::pluck('publishable_key')->first() ?? '';
    }

    public function isValidPaymentMethod(string $method): bool
    {
        return in_array($method, ['creditcard', 'mada', 'applepay']);
    }

    public function preparePaymentPageData(Order $order, string $method): array
    {
        return [
            'order' => $order,
            'publishable_key' => $this->getPublishableKey(),
            'paymentNetworks' => $this->getPaymentNetworks($method),
            'method' => $method,
            'message' => '',
        ];
    }
}
