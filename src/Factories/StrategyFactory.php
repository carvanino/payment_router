<?php

namespace Akinola\PaymentRouter\Factories;

use Akinola\PaymentRouter\Contracts\RoutingStrategyInterface;
use Akinola\PaymentRouter\Exceptions\PaymentRouterException;
use Akinola\PaymentRouter\Strategies\BalancedStrategy;
use Akinola\PaymentRouter\Strategies\BestPriceStrategy;
use Akinola\PaymentRouter\Strategies\HighestReliabilityStrategy;

class StrategyFactory
{
    /**
     * Available strategies
     */
    public const STRATEGY_BEST_PRICE = 'best_price';
    public const STRATEGY_HIGHEST_RELIABILITY = 'highest_reliability';
    public const STRATEGY_BALANCED = 'balanced';
    
    /**
     * Create a routing strategy instance.
     *
     * @param string $strategyName
     * @return RoutingStrategyInterface
     * @throws PaymentRouterException
     */
    public static function make(string $strategyName): RoutingStrategyInterface
    {
        switch ($strategyName) {
            case self::STRATEGY_BEST_PRICE:
                return new BestPriceStrategy();
            
            case self::STRATEGY_HIGHEST_RELIABILITY:
                return new HighestReliabilityStrategy();
            
            case self::STRATEGY_BALANCED:
                return new BalancedStrategy();
            
            default:
                throw new PaymentRouterException("Unknown strategy: {$strategyName}");
        }
    }
}