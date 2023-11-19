<?php


namespace App\Entities\Payments;


class PaymentStatusEntity
{
    public function __construct(
        public readonly string $gateway,
        public readonly int $paymentId,
        public readonly string $status,
        public readonly int $amount,
        public readonly int $amountPaid,
        public readonly string $sign,
        public readonly ?array $additional = null,
    ) {}
}
