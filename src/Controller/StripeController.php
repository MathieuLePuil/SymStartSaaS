<?php

namespace App\Controller;

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    #[Route('/payment', name: 'app_stripe_create-checkout-session')]
    public function createCheckoutSession(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $stripeApiKey = $_ENV['STRIPE_API_KEY'];
        $stripe = new StripeClient($stripeApiKey);

        $successUrl = 'http://localhost:8313/success/{CHECKOUT_SESSION_ID}'; // Replace with your success page
        $cancelUrl = 'http://localhost:8313/cancel'; // Replace with your cancel page

        try {
            $checkout_session = $stripe->checkout->sessions->create([
                'customer_email' => $this->getUser()->getUserIdentifier(),
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Product', // Name of your product
                        ],
                        'unit_amount' => 200, // 2.00 USD
                    ],
                    'quantity' => 1, // Quantity of the product
                ]],
                // For subscription, change 'payment' to 'subscription' in mode
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);
        } catch (ApiErrorException) {
            $this->addFlash('error', 'Error during the payment. Please try again.');

            return $this->redirectToRoute('app_home');
        }

        return $this->redirect($checkout_session->url);
    }

    #[Route('/success/{sessionId}', name: 'app_stripe_success')]
    public function success(string $sessionId): Response
    {
        $stripe = new StripeClient($_ENV['STRIPE_API_KEY']);

        try {
            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if ($session && $session->payment_status == 'paid') {
                // Add here your logic to save the order
                $this->addFlash('success', 'Congratulations for your buy !');
            } else {
                // Add here your logic to handle the payment error
                $this->addFlash('error', 'Error during the payment. Please try again.');
            }
        } catch (ApiErrorException) {
            $this->addFlash('error', 'Error during the payment. Please try again.');
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route('/cancel', name: 'app_stripe_cancel')]
    public function cancel(): Response
    {
        // Add here your logic to handle the payment cancel
        $this->addFlash('error', 'Error during the payment. Please try again.');

        return $this->redirectToRoute('app_home');
    }
}
