<?php

namespace App\Services\Payments\Gateways;

use App\Entities\Payments\PaymentStatusEntity;
use App\Repositories\Interfaces\IPaymentRepository;

class FirstPaymentGateway implements IPaymentGateway
{
    public const PAYMENT_NAME = 'first-gateway';
    private const LIMIT_PER_DAY = 50;

    public function __construct(
        private readonly IPaymentRepository $paymentRepository
    ) {}

    public function checkPaymentStatusSign(PaymentStatusEntity $entity): bool
    {
        $originalRequest = [
            'merchant_id' => $entity->additional['merchantId'],
            'payment_id' => $entity->paymentId,
            'status' => $entity->status,
            'amount' => $entity->amount,
            'amount_paid' => $entity->amountPaid,
            'timestamp' => $entity->additional['paidAt'],
        ];
        ksort($originalRequest);
        $implodedValues = implode(':', $originalRequest) . $entity->additional['merchantKey'];
        $hash = hash('sha256', $implodedValues);

        return $hash == $entity->sign;
    }

    public function checkLimit(): bool
    {
        $todayPaymentsCount = $this->paymentRepository->getTodayPaymentsCount(self::PAYMENT_NAME);
        return $todayPaymentsCount < self::LIMIT_PER_DAY;
    }
}
