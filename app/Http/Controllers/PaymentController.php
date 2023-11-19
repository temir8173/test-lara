<?php

namespace App\Http\Controllers;

use App\Entities\Payments\PaymentStatusEntity;
use App\Services\Payments\Gateways\FirstPaymentGateway;
use App\Services\Payments\Gateways\SecondPaymentGateway;
use App\Services\Payments\IPaymentService;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        public readonly IPaymentService $paymentService
    ) {}

    public function firstMerchantCallback(Request $request): JsonResponse
    {
        $response = [
            'success' => false
        ];

        $entity = new PaymentStatusEntity(
            gateway: FirstPaymentGateway::PAYMENT_NAME,
            paymentId: $request->json()->getInt('payment_id'),
            status: $request->json()->get('status'),
            amount: $request->json()->getInt('amount'),
            amountPaid: $request->json()->getInt('amount_paid'),
            sign: $request->json()->get('sign'),
            additional: [
                'merchantId' => $request->json()->get('merchant_id'),
                'merchantKey' => env('FIRST_PAYMENT_MERCHANT_KEY'),
                'paidAt' => $request->json()->get('timestamp'),
            ]
        );

        $response['success'] = $this->paymentService->changeStatus($entity);

        return response()->json($response);
    }

    public function secondMerchantCallback(Request $request): JsonResponse
    {
        $response = [
            'success' => false
        ];

        $entity = new PaymentStatusEntity(
            gateway: SecondPaymentGateway::PAYMENT_NAME,
            paymentId: $request->post('invoice'),
            status: $request->post('status'),
            amount: $request->post('amount'),
            amountPaid: $request->post('amount_paid'),
            sign: $request->header('Authorization'),
            additional: [
                'merchantId' => $request->post('project'),
                'merchantKey' => env('SECOND_PAYMENT_APP_KEY'),
                'rand' => $request->post('rand'),
            ]
        );

        $response['success'] = $this->paymentService->changeStatus($entity);

        return response()->json($response);
    }
}
