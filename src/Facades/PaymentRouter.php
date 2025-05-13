<?php

namespace Akinola\PaymentRouter\Facades;

use Illuminate\Support\Facades\Facade;
use Akinola\PaymentRouter\Contracts\PaymentRouterInterface;

/**
 * @method static \Akinola\PaymentRouter\Contracts\PaymentRouterInterface registerProcessor(\Akinola\PaymentRouter\Contracts\PaymentProcessorInterface $processor)
 * @method static \Akinola\PaymentRouter\Contracts\PaymentRouterInterface removeProcessor(string $processorName)
 * @method static array getProcessors()
 * @method static \Akinola\PaymentRouter\Contracts\PaymentProcessorInterface route(\Akinola\PaymentRouter\DTO\TransactionData $transactionData, ?string $strategy = null)
 * @method static array processPayment(\Akinola\PaymentRouter\DTO\TransactionData $transactionData, ?string $strategy = null)
 * 
 * @see \Akinola\PaymentRouter\Services\PaymentRouter
 */
class PaymentRouter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PaymentRouterInterface::class;
    }
}