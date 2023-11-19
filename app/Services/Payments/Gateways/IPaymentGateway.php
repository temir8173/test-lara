<?php

namespace App\Services\Payments\Gateways;

use App\Entities\Payments\PaymentStatusEntity;

interface IPaymentGateway
{
    public function checkPaymentStatusSign(PaymentStatusEntity $entity): bool;
    public function checkLimit(): bool;
}
