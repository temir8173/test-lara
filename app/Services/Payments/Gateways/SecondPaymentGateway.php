<?php

namespace App\Services\Payments\Gateways;

use App\Entities\Payments\PaymentStatusEntity;
use App\Repositories\Interfaces\IPaymentRepository;

class SecondPaymentGateway implements IPaymentGateway
{
    public const PAYMENT_NAME = 'second-gateway';
    private const LIMIT_PER_DAY = 100;

    public function __construct(
        private readonly IPaymentRepository $paymentRepository
    ) {}

    public function checkPaymentStatusSign(PaymentStatusEntity $entity): bool
    {
        $originalRequest = [
            'project' => $entity->additional['merchantId'],
            'invoice' => $entity->paymentId,
            'status' => $entity->status,
            'amount' => $entity->amount,
            'amount_paid' => $entity->amountPaid,
            'rand' => $entity->additional['rand'],
        ];
        ksort($originalRequest);
        $implodedValues = implode('.', $originalRequest) . $entity->additional['merchantKey'];
        $hash = md5($implodedValues);

        return $hash == $entity->sign;
    }

    public function checkLimit(): bool
    {
        $todayPaymentsCount = $this->paymentRepository->getTodayPaymentsCount(self::PAYMENT_NAME);
        return $todayPaymentsCount < self::LIMIT_PER_DAY;
    }
}
