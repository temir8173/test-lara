<?php

namespace App\Services\Payments;

use App\Entities\Payments\PaymentStatusEntity;
use App\Repositories\Interfaces\IPaymentRepository;
use App\Services\Payments\Gateways\FirstPaymentGateway;
use App\Services\Payments\Gateways\IPaymentGateway;
use App\Services\Payments\Gateways\SecondPaymentGateway;

class PaymentService implements IPaymentService
{
    public const FIRST_PAYMENT = 'first-payment';
    public const SECOND_PAYMENT = 'second-payment';

    private IPaymentGateway $gateway;

    public function __construct(
        private readonly IPaymentRepository $repository
    ) {}

    public function setGateway(string $gateway): PaymentService
    {
        $gatewayClass = match ($gateway) {
            self::FIRST_PAYMENT => FirstPaymentGateway::class,
            self::SECOND_PAYMENT => SecondPaymentGateway::class,
        };

        $this->gateway = new $gatewayClass();
        return $this;
    }

    public function changeStatus(PaymentStatusEntity $entity): bool
    {
        $this->setGateway($entity->gateway);
        $isSignValid = $this->gateway->checkPaymentStatusSign($entity);

        $id = 0;
        if ($isSignValid) {
            $id = $this->repository->changeStatus($entity);
        }

        return (bool)$id;
    }
}
