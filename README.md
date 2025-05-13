# Laravel Payment Router

A smart payment gateway routing system for Laravel applications that intelligently routes transactions to the most suitable payment processor based on various factors like cost, reliability, and currency support.

## Features

- Smart routing based on multiple strategies
-  Support for multiple payment processors
-  Easy integration of new processors via Adapter Pattern
-  Transaction monitoring and logging
-  Built-in error handling and fallback support
-  Secure payment processing
-  Performance-focused architecture

## Requirements

- PHP 8.0 or higher
- Laravel 8.0 or higher

## Installation

Install the package via composer:

```bash
composer require akinola/payment-router
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Akinola\PaymentRouter\PaymentRouterServiceProvider"
```

## Configuration

Add your payment processor credentials to your `.env` file:

```env
STRIPE_SECRET_KEY=your_stripe_secret_key
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_secret
FLUTTERWAVE_SECRET_KEY=your_flutterwave_secret_key
```

Configure processors in `config/payment-router.php`:

```php
return [
    'processors' => [
        'stripe' => [
            'class' => \Akinola\PaymentRouter\Processors\StripeProcessor::class,
            'active' => true,
            'transaction_fee_percentage' => 2.9,
            'transaction_fee_fixed' => 0.30,
            'reliability_score' => 0.98,
            'supported_currencies' => ['USD', 'EUR', 'GBP'],
            'supported_countries' => ['US', 'GB', 'FR']
        ],
        // Add other processors here
    ],
    'default_strategy' => 'best_price',
    'logging' => [
        'enabled' => true,
        'channel' => 'stack'
    ]
];
```

## Usage

### Basic Usage

```php
use Akinola\PaymentRouter\Facades\PaymentRouter;
use Akinola\PaymentRouter\DTO\TransactionData;

// Create transaction data
$transaction = new TransactionData([
    'amount' => 1000,
    'currency' => 'USD',
    'description' => 'Test payment',
    'metadata' => [
        'customer_email' => 'customer@example.com'
    ]
]);

// Process payment with default strategy
try {
    $result = PaymentRouter::processPayment($transaction);
    // Handle successful payment
} catch (PaymentProcessorException $e) {
    // Handle payment processing error
}
```

### Using Different Routing Strategies

```php
// Use reliability-based routing
$result = PaymentRouter::processPayment($transaction, 'reliability');

// Use balanced routing
$result = PaymentRouter::processPayment($transaction, 'balanced');
```

### Adding Custom Processors

1. Create your processor class:

```php
use Akinola\PaymentRouter\Contracts\PaymentProcessorInterface;

class CustomProcessor implements PaymentProcessorInterface
{
    public function processPayment(array $data): array
    {
        // Implement payment processing
    }

    // Implement other required methods
}
```

2. Register your processor:

```php
PaymentRouter::registerProcessor(new CustomProcessor());
```

## Testing

## Demo Application

Check out our [demo application](https://github.com/yourusername/payment-router-demo) for a complete implementation example.

## Security

If you discover any security-related issues, please email akinolatofunmi.tech@gmail.com instead of using the issue tracker.

## Credits

- [Akinola](https://github.com/carvanino)