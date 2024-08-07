<?php

namespace App\Services\PayGateways;

use Stripe\StripeClient;

class StripePaymentService
{
    public $stripeRequest;
    public function __construct($secretKey = null)
    {
        $this->stripeRequest = new StripeClient(config("services.stripe.private_key"));
    }

    public function createCustomer($name, $email)
    {
        $response = $this->stripeRequest->customers->create([
            "name" => $name,
            "email" => $email
        ]);
        return $response;
    }

    private function createCardPaymentMethod($data)
    {
        $response = $this->stripeRequest->paymentMethods->create([
            'type' => 'card',
            'card' => $data
        ]);
        return $response;
    }
    private function attachPaymentMethod($cus_id, $pm_id)
    {
        $response = $this->stripeRequest->paymentMethods->attach(
            $pm_id,
            [
                'customer' => $cus_id
            ]
        );
    }

    public function updateCustomer()
    {
    }

    public function InitCheckOut($ref, $data, $rdr = null)
    {
        // return $data;
        $response = $this->stripeRequest->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $data["currency"],
                    'product_data' => [
                        'name' => $data['name']
                    ],
                    'unit_amount' => $data['amount'] * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $rdr ? "$rdr?status=success&ref=$ref" : null,
            'cancel_url' => $rdr ? "$rdr?status=canceled&ref=$ref" : null,
            "metadata" => [
                "transRef" => $ref,
            ]
        ]);
        return  $response;
    }

    public function verifyStripeWebhook($payload)
    {
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $wbhKey = config("services.stripe.webhook_key");
        $event = null;
        try {
            return $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $wbhKey
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }
    }
}
