<?php

namespace App\Services\Payments;

use App\Models\Donation;

interface PaymentGatewayInterface
{
    /**
     * Create a payment checkout session and return the payment url/session id.
     */
    public function createCheckout(Donation $donation): array;

    /**
     * Handle payment webhook callback.
     */
    public function handleWebhook(array $data): void;
}
