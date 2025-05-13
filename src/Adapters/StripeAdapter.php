<?php

namespace Akinola\PaymentRouter\Adapters;

use Akinola\PaymentRouter\Abstracts\AbstractPaymentProcessor;
use Akinola\PaymentRouter\Exceptions\PaymentProcessorException;

class StripeAdapter extends AbstractPaymentProcessor
{
    /**
     * The Stripe API key.
     *
     * @var string|null
     */
    protected ?string $apiKey;
    
    /**
     * Create a new StripeAdapter instance.
     *
     * @param string $name
     * @param bool $active
     * @param float $transactionFeePercentage
     * @param float $transactionFeeFixed
     * @param float $reliabilityScore
     * @param array $supportedCurrencies
     * @param array $supportedCountries
     * @param string|null $apiKey
     */
    public function __construct(
        string $name = 'Stripe',
        bool $active = true,
        float $transactionFeePercentage = 2.9,
        float $transactionFeeFixed = 0.30,
        float $reliabilityScore = 95,
        array $supportedCurrencies = ['USD', 'EUR', 'GBP', 'NGN'],
        array $supportedCountries = ['US', 'UK', 'NG', '*'],
        ?string $apiKey = null
    ) {
        parent::__construct(
            $name,
            $active,
            $transactionFeePercentage,
            $transactionFeeFixed,
            $reliabilityScore,
            $supportedCurrencies,
            $supportedCountries
        );
        
        $this->apiKey = $apiKey ?? config('payment-router.processors.stripe.config.api_key');
    }
    
    /**
     * Process a payment transaction through Stripe.
     *
     * @param array $paymentData
     * @return array
     * @throws PaymentProcessorException
     */
    public function processPayment(array $paymentData): array
    {
        if (empty($this->apiKey)) {
            throw new PaymentProcessorException('Stripe API key is not configured');
        }
        
        // In a real implementation, you would integrate with the Stripe API here
        // For this example, we'll simulate a successful payment
        
        // Validate required fields
        $requiredFields = ['amount', 'currency', 'payment_details'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentData[$field])) {
                throw new PaymentProcessorException("Missing required field: {$field}");
            }
        }
        
        // Check if payment details contain required information
        if (!isset($paymentData['payment_details']['card_token'])) {
            throw new PaymentProcessorException('Missing card token in payment details');
        }
        
        // Check currency support
        if (!$this->supportsCurrency($paymentData['currency'])) {
            throw new PaymentProcessorException("Currency not supported: {$paymentData['currency']}");
        }
        
        // Simulate API call
        // In a real implementation, you would use Stripe's SDK here
        
        // Return a simulated successful response
        return [
            'transaction_id' => 'stripe_' . uniqid(),
            'status' => 'success',
            'processor' => $this->getName(),
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'fee' => $this->getTransactionFee($paymentData['amount'], $paymentData['currency']),
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }
}