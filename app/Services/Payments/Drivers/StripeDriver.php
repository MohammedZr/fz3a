<?php

namespace App\Services\Payments\Drivers;

use App\Models\Donation;
use App\Models\Campaign;
use App\Models\Payment;
use App\Services\Payments\PaymentGatewayInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Illuminate\Support\Facades\Log;
use Exception;

class StripeDriver implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckout(Donation $donation): array
    {
        if (!$donation->amount) {
            throw new Exception("Donation amount is required.");
        }

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($donation->currency),
                    'unit_amount' => intval($donation->amount * 100),
                    'product_data' => [
                        'name' => 'Donation to FZ3A',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('payment.cancel'),
            'metadata' => [
                'donation_id' => $donation->id,
            ]
        ]);

        // تسجيل سجل الدفع
        Payment::create([
            'donation_id'       => $donation->id,
            'payment_method_id' => null,
            'amount'            => $donation->amount,
            'currency'          => $donation->currency,
            'status'            => 'initiated',
            'provider'          => 'stripe',
            'provider_payment_id' => $session->id,
        ]);

        return [
            'checkout_url' => $session->url,
            'session_id'   => $session->id
        ];
    }

    public function handleWebhook(array $data): void
    {
        $event = $data['type'] ?? null;

        if ($event === 'checkout.session.completed') {
            $session = $data['data']['object'];
            $donationId = $session['metadata']['donation_id'] ?? null;

            $donation = Donation::find($donationId);
            if (!$donation) return;

            // تحديث حالة الدفع
            $payment = Payment::where('provider_payment_id', $session['id'])->first();
            if ($payment) {
                $payment->status = 'succeeded';
                $payment->save();
            }

            // تحديث التبرع
            $donation->status = 'paid';
            $donation->save();

            // تحديث الحملة
            if ($donation->campaign) {
                $donation->campaign->increment('raised_amount', $donation->amount);
            }
        }
    }
}
