<?php

namespace Akinola\PaymentRouter\Contracts;

use Akinola\PaymentRouter\DTO\TransactionData;

interface RoutingStrategyInterface
{
    /**
     * Select the best processor for a transaction based on specific criteria.
     *
     * @param array $processors
     * @param TransactionData $transactionData
     * @return PaymentProcessorInterface|null
     */
    public function selectProcessor(array $processors, TransactionData $transactionData): ?PaymentProcessorInterface;
}