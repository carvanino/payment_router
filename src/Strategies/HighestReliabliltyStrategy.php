<?php

namespace Akinola\PaymentRouter\Strategies;

use Akinola\PaymentRouter\Contracts\PaymentProcessorInterface;
use Akinola\PaymentRouter\Contracts\RoutingStrategyInterface;
use Akinola\PaymentRouter\DTO\TransactionData;

class HighestReliabilityStrategy implements RoutingStrategyInterface
{
    /**
     * Select the processor with the highest reliability score.
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
            
            $eligibleProcessors[] = [
                'processor' => $processor,
                'score' => $processor->getReliabilityScore(),
            ];
        }
        
        if (empty($eligibleProcessors)) {
            return null;
        }
        
        // Sort by reliability score (highest first)
        usort($eligibleProcessors, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Return the processor with the highest reliability score
        return $eligibleProcessors[0]['processor'];
    }
}