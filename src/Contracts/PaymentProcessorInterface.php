<?php

namespace Akinola\PaymentRouter\Contracts;

interface PaymentProcessorInterface
{
    /**
     * Get the name of the payment processor.
     *
     * @return string
     */
    public function getName(): string;
    
    /**
     * Process a payment transaction.
     *
     * @param array $paymentData
     * @return array
     */
    public function processPayment(array $paymentData): array;
    
    /**
     * Check if the processor is active.
     *
     * @return bool
     */
    public function isActive(): bool;
    
    /**
     * Get the transaction fee for a given amount.
     *
     * @param float $amount
     * @param string $currency
     * @return float
     */
    public function getTransactionFee(float $amount, string $currency): float;
    
    /**
     * Get the reliability score of the processor.
     *
     * @return float
     */
    public function getReliabilityScore(): float;
    
    /**
     * Check if the processor supports a given currency.
     *
     * @param string $currency
     * @return bool
     */
    public function supportsCurrency(string $currency): bool;
    
    /**
     * Check if the processor supports a given country.
     *
     * @param string $countryCode
     * @return bool
     */
    public function supportsCountry(string $countryCode): bool;
}