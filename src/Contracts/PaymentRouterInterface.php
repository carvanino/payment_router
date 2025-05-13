<?php

namespace Akinola\PaymentRouter\Contracts;

use Akinola\PaymentRouter\Contracts\PaymentProcessorInterface;
use Akinola\PaymentRouter\DTO\TransactionData;

interface PaymentRouterInterface
{
    /**
     * Register a payment processor with the router.
     *
     * @param PaymentProcessorInterface $processor
     * @return self
     */
    public function registerProcessor(PaymentProcessorInterface $processor): self;
    
    /**
     * Remove a payment processor from the router.
     *
     * @param string $processorName
     * @return self
     */
    public function removeProcessor(string $processorName): self;
    
    /**
     * Get all registered payment processors.
     *
     * @return array
     */
    public function getProcessors(): array;
    
    /**
     * Route a payment transaction to the best processor.
     *
     * @param TransactionData $transactionData
     * @param string|null $strategy
     * @return PaymentProcessorInterface
     */
    public function route(TransactionData $transactionData, ?string $strategy = null): PaymentProcessorInterface;
    
    /**
     * Process a payment with the best processor.
     *
     * @param TransactionData $transactionData
     * @param string|null $strategy
     * @return array
     */
    public function processPayment(TransactionData $transactionData, ?string $strategy = null): array;
}