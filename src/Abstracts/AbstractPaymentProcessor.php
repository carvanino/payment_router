<?php

namespace Akinola\PaymentRouter\Abstracts;

use Akinola\PaymentRouter\Contracts\PaymentProcessorInterface;
use Akinola\PaymentRouter\Exceptions\PaymentProcessorException;

abstract class AbstractPaymentProcessor implements PaymentProcessorInterface
{
    /**
     * The processor name.
     *
     * @var string
     */
    protected string $name;
    
    /**
     * Indicates if the processor is active.
     *
     * @var bool
     */
    protected bool $active;
    
    /**
     * The transaction fee percentage.
     *
     * @var float
     */
    protected float $transactionFeePercentage;
    
    /**
     * The fixed transaction fee.
     *
     * @var float
     */
    protected float $transactionFeeFixed;
    
    /**
     * The reliability score (0-100).
     *
     * @var float
     */
    protected float $reliabilityScore;
    
    /**
     * Supported currencies.
     *
     * @var array
     */
    protected array $supportedCurrencies;
    
    /**
     * Supported countries.
     *
     * @var array
     */
    protected array $supportedCountries;
    
    /**
     * Create a new payment processor instance.
     *
     * @param string $name
     * @param bool $active
     * @param float $transactionFeePercentage
     * @param float $transactionFeeFixed
     * @param float $reliabilityScore
     * @param array $supportedCurrencies
     * @param array $supportedCountries
     */
    public function __construct(
        string $name,
        bool $active = true,
        float $transactionFeePercentage = 0,
        float $transactionFeeFixed = 0,
        float $reliabilityScore = 0,
        array $supportedCurrencies = [],
        array $supportedCountries = []
    ) {
        $this->name = $name;
        $this->active = $active;
        $this->transactionFeePercentage = $transactionFeePercentage;
        $this->transactionFeeFixed = $transactionFeeFixed;
        $this->reliabilityScore = $reliabilityScore;
        $this->supportedCurrencies = array_map('strtoupper', $supportedCurrencies);
        $this->supportedCountries = array_map('strtoupper', $supportedCountries);
    }
    
    /**
     * Get the name of the payment processor.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Check if the processor is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
    
    /**
     * Get the transaction fee for a given amount.
     *
     * @param float $amount
     * @param string $currency
     * @return float
     * @throws PaymentProcessorException
     */
    public function getTransactionFee(float $amount, string $currency): float
    {
        $currency = strtoupper($currency);
        
        if (!$this->supportsCurrency($currency)) {
            throw new PaymentProcessorException("Currency {$currency} not supported by {$this->name}");
        }
        
        return ($amount * $this->transactionFeePercentage / 100) + $this->transactionFeeFixed;
    }
    
    /**
     * Get the reliability score of the processor.
     *
     * @return float
     */
    public function getReliabilityScore(): float
    {
        return $this->reliabilityScore;
    }
    
    /**
     * Check if the processor supports a given currency.
     *
     * @param string $currency
     * @return bool
     */
    public function supportsCurrency(string $currency): bool
    {
        $currency = strtoupper($currency);
        
        return in_array('*', $this->supportedCurrencies) || in_array($currency, $this->supportedCurrencies);
    }
    
    /**
     * Check if the processor supports a given country.
     *
     * @param string $countryCode
     * @return bool
     */
    public function supportsCountry(string $countryCode): bool
    {
        $countryCode = strtoupper($countryCode);
        
        return in_array('*', $this->supportedCountries) || in_array($countryCode, $this->supportedCountries);
    }
    
    /**
     * Process a payment transaction.
     *
     * @param array $paymentData
     * @return array
     * @throws PaymentProcessorException
     */
    abstract public function processPayment(array $paymentData): array;
}