<?php

namespace Akinola\PaymentRouter\DTO;

class TransactionData
{
    /**
     * @var float
     */
    public float $amount;
    
    /**
     * @var string
     */
    public string $currency;
    
    /**
     * @var string
     */
    public string $countryCode;
    
    /**
     * @var array
     */
    public array $paymentDetails;
    
    /**
     * Create a new TransactionData instance.
     *
     * @param float $amount
     * @param string $currency
     * @param string $countryCode
     * @param array $paymentDetails
     */
    public function __construct(
        float $amount,
        string $currency,
        string $countryCode,
        array $paymentDetails = []
    ) {
        $this->amount = $amount;
        $this->currency = strtoupper($currency);
        $this->countryCode = strtoupper($countryCode);
        $this->paymentDetails = $paymentDetails;
    }
    
    /**
     * Create a TransactionData instance from an array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['amount'],
            $data['currency'],
            $data['country_code'],
            $data['payment_details'] ?? []
        );
    }
    
    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'country_code' => $this->countryCode,
            'payment_details' => $this->paymentDetails,
        ];
    }
}