<?php

namespace App\Services\Payments\Gateways;

use App\Entities\Payments\PaymentStatusEntity;

class FirstPaymentGateway implements IPaymentGateway
{
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
        return true;
    }
}
