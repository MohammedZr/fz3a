<?php

namespace App\Services\Payments;

use App\Models\Donation;
use App\Services\Payments\Drivers\StripeDriver;

class PaymentService
{
    public function driver(): PaymentGatewayInterface
    {
        $default = config('payments.default');

        return match ($default) {
            'stripe' => new StripeDriver(),
            default  => new StripeDriver(), // fallback
        };
    }

    public function checkout(Donation $donation): array
    {
        return $this->driver()->createCheckout($donation);
    }

    public function webhook(array $data): void
    {
        $this->driver()->handleWebhook($data);
    }
}
