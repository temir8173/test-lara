<?php

namespace App\Services\Payments\Gateways;

use App\Entities\Payments\PaymentStatusEntity;

class SecondPaymentGateway implements IPaymentGateway
{
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
        return true;
    }
}
