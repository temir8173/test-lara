<?php

namespace App\Services\Payments;

use App\Entities\Payments\PaymentStatusEntity;

interface IPaymentService
{
    public function setGateway(string $gateway): IPaymentService;
    public function changeStatus(PaymentStatusEntity $entity): bool;
}
