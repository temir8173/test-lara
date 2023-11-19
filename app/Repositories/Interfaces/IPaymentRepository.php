<?php


namespace App\Repositories\Interfaces;


use App\Entities\Payments\PaymentStatusEntity;

interface IPaymentRepository
{
    public function changeStatus(PaymentStatusEntity $entity): int;
}
