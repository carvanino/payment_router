<?php

namespace Akinola\PaymentRouter\Adapters;

use Akinola\PaymentRouter\Abstracts\AbstractPaymentProcessor;
use Akinola\PaymentRouter\Exceptions\PaymentProcessorException;

class FlutterwaveAdapter extends AbstractPaymentProcessor
{
    /**
     * The Flutterwave API key.
     *
     * @var string|null
     */
    protected ?string $apiKey;
    
    /**
     * Create a new FlutterwaveAdapter instance.
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
        string $name = 'Flutterwave',
        bool $active = true,
        float $transactionFeePercentage = 1.4,
        float $transactionFeeFixed = 0.20,
        float $reliabilityScore = 85,
        array $supportedCurrencies = ['NGN', 'USD', 'GHS', 'KES', 'ZAR'],
        array $supportedCountries = ['NG', 'GH', 'KE', 'ZA', 'US'],
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
        
        $this->apiKey = $apiKey ?? config('payment-router.processors.flutterwave.config.api_key');
    }
    
    /**
     * Process a payment transaction through Flutterwave.
     *
     * @param array $paymentData
     * @return array
     * @throws PaymentProcessorException
     */
    public function processPayment(array $paymentData): array
    {
        if (empty($this->apiKey)) {
            throw new PaymentProcessorException('Flutterwave API key is not configured');
        }
        
        // In a real implementation, you would integrate with the Flutterwave API here
        // For this example, we'll simulate a successful payment
        
        // Validate required fields
        $requiredFields = ['amount', 'currency', 'payment_details'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentData[$field])) {
                throw new PaymentProcessorException("Missing required field: {$field}");
            }
        }
        
        // Check if payment details contain required information
        if (!isset($paymentData['payment_details']['tx_ref'])) {
            throw new PaymentProcessorException('Missing transaction reference in payment details');
        }
        
        // Check currency support
        if (!$this->supportsCurrency($paymentData['currency'])) {
            throw new PaymentProcessorException("Currency not supported: {$paymentData['currency']}");
        }
        
        // Check country support
        if (isset($paymentData['country_code']) && !$this->supportsCountry($paymentData['country_code'])) {
            throw new PaymentProcessorException("Country not supported: {$paymentData['country_code']}");
        }
        
        // Simulate API call
        // In a real implementation, you would use Flutterwave's SDK here
        
        // Return a simulated successful response
        return [
            'transaction_id' => 'flw_' . uniqid(),
            'status' => 'success',
            'processor' => $this->getName(),
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'fee' => $this->getTransactionFee($paymentData['amount'], $paymentData['currency']),
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }
}