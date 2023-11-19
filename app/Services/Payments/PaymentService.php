<?php

namespace App\Services\Payments;

use App\Entities\Payments\PaymentStatusEntity;
use App\Repositories\Interfaces\IPaymentRepository;
use App\Services\Payments\Gateways\FirstPaymentGateway;
use App\Services\Payments\Gateways\IPaymentGateway;
use App\Services\Payments\Gateways\SecondPaymentGateway;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

class PaymentService implements IPaymentService
{
    private IPaymentGateway $gateway;

    public function __construct(
        private readonly IPaymentRepository $repository,
        private readonly Container $container,
    ) {}

    /**
     * @throws BindingResolutionException
     */
    public function setGateway(string $gateway): PaymentService
    {
        $gatewayClass = match ($gateway) {
            FirstPaymentGateway::PAYMENT_NAME => FirstPaymentGateway::class,
            SecondPaymentGateway::PAYMENT_NAME => SecondPaymentGateway::class,
        };

        $this->gateway = $this->container->make($gatewayClass);
        return $this;
    }

    /**
     * @throws BindingResolutionException
     */
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
