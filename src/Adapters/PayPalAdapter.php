<?php

namespace Akinola\PaymentRouter\Adapters;

use Akinola\PaymentRouter\Abstracts\AbstractPaymentProcessor;
use Akinola\PaymentRouter\Exceptions\PaymentProcessorException;

class PayPalAdapter extends AbstractPaymentProcessor
{
    /**
     * The PayPal client ID.
     *
     * @var string|null
     */
    protected ?string $clientId;
    
    /**
     * The PayPal client secret.
     *
     * @var string|null
     */
    protected ?string $clientSecret;
    
    /**
     * Create a new PayPalAdapter instance.
     *
     * @param string $name
     * @param bool $active
     * @param float $transactionFeePercentage
     * @param float $transactionFeeFixed
     * @param float $reliabilityScore
     * @param array $supportedCurrencies
     * @param array $supportedCountries
     * @param string|null $clientId
     * @param string|null $clientSecret
     */
    public function __construct(
        string $name = 'PayPal',
        bool $active = true,
        float $transactionFeePercentage = 3.4,
        float $transactionFeeFixed = 0.30,
        float $reliabilityScore = 90,
        array $supportedCurrencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
        array $supportedCountries = ['US', 'CA', 'UK', 'AU', 'DE', 'FR', '*'],
        ?string $clientId = null,
        ?string $clientSecret = null
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
        
        $this->clientId = $clientId ?? config('payment-router.processors.paypal.config.client_id');
        $this->clientSecret = $clientSecret ?? config('payment-router.processors.paypal.config.client_secret');
    }
    
    /**
     * Process a payment transaction through PayPal.
     *
     * @param array $paymentData
     * @return array
     * @throws PaymentProcessorException
     */
    public function processPayment(array $paymentData): array
    {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new PaymentProcessorException('PayPal client credentials are not configured');
        }
        
        // In a real implementation, you would integrate with the PayPal API here
        // For this example, we'll simulate a successful payment
        
        // Validate required fields
        $requiredFields = ['amount', 'currency', 'payment_details'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentData[$field])) {
                throw new PaymentProcessorException("Missing required field: {$field}");
            }
        }
        
        // Check if payment details contain required information
        if (!isset($paymentData['payment_details']['payer_id'])) {
            throw new PaymentProcessorException('Missing payer ID in payment details');
        }
        
        // Check currency support
        if (!$this->supportsCurrency($paymentData['currency'])) {
            throw new PaymentProcessorException("Currency not supported: {$paymentData['currency']}");
        }
        
        // Simulate API call
        // In a real implementation, you would use PayPal's SDK here
        
        // Return a simulated successful response
        return [
            'transaction_id' => 'paypal_' . uniqid(),
            'status' => 'success',
            'processor' => $this->getName(),
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'fee' => $this->getTransactionFee($paymentData['amount'], $paymentData['currency']),
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }
}