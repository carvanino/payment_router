<?php

namespace Akinola\PaymentRouter\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Akinola\PaymentRouter\Contracts\PaymentProcessorInterface;
use Akinola\PaymentRouter\Contracts\PaymentRouterInterface;
use Akinola\PaymentRouter\DTO\TransactionData;
use Akinola\PaymentRouter\Exceptions\PaymentProcessorException;
use Akinola\PaymentRouter\Exceptions\PaymentRouterException;
use Akinola\PaymentRouter\Factories\StrategyFactory;

class PaymentRouter implements PaymentRouterInterface
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected Application $app;
    
    /**
     * Array of registered payment processors.
     *
     * @var array
     */
    protected array $processors = [];
    
    /**
     * Create a new PaymentRouter instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->loadConfiguredProcessors();
    }
    
    /**
     * Load processors from configuration.
     *
     * @return void
     */
    protected function loadConfiguredProcessors(): void
    {
        $processors = config('payment-router.processors', []);
        
        foreach ($processors as $key => $config) {
            if (!isset($config['class']) || !class_exists($config['class'])) {
                $this->logWarning("Invalid processor class for {$key}");
                continue;
            }
            
            if (!isset($config['active']) || $config['active'] !== true) {
                continue;
            }
            
            try {
                $processor = new $config['class'](
                    $config['name'] ?? $key,
                    $config['active'] ?? true,
                    $config['transaction_fee_percentage'] ?? 0,
                    $config['transaction_fee_fixed'] ?? 0,
                    $config['reliability_score'] ?? 0,
                    $config['supported_currencies'] ?? [],
                    $config['supported_countries'] ?? []
                );
                
                if (!($processor instanceof PaymentProcessorInterface)) {
                    $this->logWarning("{$key} does not implement PaymentProcessorInterface");
                    continue;
                }
                
                $this->processors[$key] = $processor;
            } catch (\Throwable $e) {
                $this->logWarning("Failed to initialize processor {$key}: {$e->getMessage()}");
            }
        }
    }
    
    /**
     * Register a payment processor with the router.
     *
     * @param PaymentProcessorInterface $processor
     * @return self
     */
    public function registerProcessor(PaymentProcessorInterface $processor): self
    {
        $this->processors[$processor->getName()] = $processor;
        
        return $this;
    }
    
    /**
     * Remove a payment processor from the router.
     *
     * @param string $processorName
     * @return self
     */
    public function removeProcessor(string $processorName): self
    {
        if (isset($this->processors[$processorName])) {
            unset($this->processors[$processorName]);
        }
        
        return $this;
    }
    
    /**
     * Get all registered payment processors.
     *
     * @return array
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }
    
    /**
     * Route a payment transaction to the best processor.
     *
     * @param TransactionData $transactionData
     * @param string|null $strategy
     * @return PaymentProcessorInterface
     * @throws PaymentRouterException
     */
    public function route(TransactionData $transactionData, ?string $strategy = null): PaymentProcessorInterface
    {
        $strategyName = $strategy ?? config('payment-router.default_strategy', StrategyFactory::STRATEGY_BEST_PRICE);
        
        try {
            $strategyInstance = StrategyFactory::make($strategyName);
        } catch (PaymentRouterException $e) {
            $this->logError("Invalid routing strategy: {$strategyName}");
            throw $e;
        }
        
        $processor = $strategyInstance->selectProcessor(array_values($this->processors), $transactionData);
        
        if ($processor === null) {
            $this->logError("No suitable processor found for transaction");
            throw new PaymentRouterException("No suitable payment processor found for this transaction");
        }
        
        $this->logInfo("Selected processor: {$processor->getName()} using strategy: {$strategyName}");
        
        return $processor;
    }
    
    /**
     * Process a payment with the best processor.
     *
     * @param TransactionData $transactionData
     * @param string|null $strategy
     * @return array
     * @throws PaymentRouterException|PaymentProcessorException
     */
    public function processPayment(TransactionData $transactionData, ?string $strategy = null): array
    {
        $processor = $this->route($transactionData, $strategy);
        
        try {
            $result = $processor->processPayment($transactionData->toArray());
            
            $this->logInfo("Payment processed successfully with {$processor->getName()}");
            
            return [
                'success' => true,
                'processor' => $processor->getName(),
                'result' => $result,
            ];
        } catch (PaymentProcessorException $e) {
            $this->logError("Payment processing failed with {$processor->getName()}: {$e->getMessage()}");
            
            throw $e;
        }
    }
    
    /**
     * Log an informational message.
     *
     * @param string $message
     * @return void
     */
    protected function logInfo(string $message): void
    {
        if (config('payment-router.logging.enabled', false)) {
            Log::channel(config('payment-router.logging.channel', 'stack'))
                ->info("[PaymentRouter] {$message}");
        }
    }
    
    /**
     * Log a warning message.
     *
     * @param string $message
     * @return void
     */
    protected function logWarning(string $message): void
    {
        if (config('payment-router.logging.enabled', false)) {
            Log::channel(config('payment-router.logging.channel', 'stack'))
                ->warning("[PaymentRouter] {$message}");
        }
    }
    
    /**
     * Log an error message.
     *
     * @param string $message
     * @return void
     */
    protected function logError(string $message): void
    {
        if (config('payment-router.logging.enabled', false)) {
            Log::channel(config('payment-router.logging.channel', 'stack'))
                ->error("[PaymentRouter] {$message}");
        }
    }
}