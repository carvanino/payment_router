<?php

namespace Akinola\PaymentRouter\Strategies;

use Akinola\PaymentRouter\Contracts\PaymentProcessorInterface;
use Akinola\PaymentRouter\Contracts\RoutingStrategyInterface;
use Akinola\PaymentRouter\DTO\TransactionData;
use Akinola\PaymentRouter\Exceptions\PaymentProcessorException;

class BestPriceStrategy implements RoutingStrategyInterface
{
    /**
     * Select the processor with the lowest transaction fee.
     *
     * @param array $processors
     * @param TransactionData $transactionData
     * @return PaymentProcessorInterface|null
     */
    public function selectProcessor(array $processors, TransactionData $transactionData): ?PaymentProcessorInterface
    {
        if (empty($processors)) {
            return null;
        }
        
        $eligibleProcessors = [];
        
        // Filter processors that are active and support the currency and country
        foreach ($processors as $processor) {
            if (!$processor->isActive()) {
                continue;
            }
            
            if (!$processor->supportsCurrency($transactionData->currency)) {
                continue;
            }
            
            if (!$processor->supportsCountry($transactionData->countryCode)) {
                continue;
            }
            
            try {
                $fee = $processor->getTransactionFee($transactionData->amount, $transactionData->currency);
                $eligibleProcessors[] = [
                    'processor' => $processor,
                    'fee' => $fee,
                ];
            } catch (PaymentProcessorException $e) {
                // Skip processors that throw exceptions
                continue;
            }
        }
        
        if (empty($eligibleProcessors)) {
            return null;
        }
        
        // Sort by fee (lowest first)
        usort($eligibleProcessors, function ($a, $b) {
            return $a['fee'] <=> $b['fee'];
        });
        
        // Return the processor with the lowest fee
        return $eligibleProcessors[0]['processor'];
    }
}