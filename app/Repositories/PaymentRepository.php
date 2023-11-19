<?php


namespace App\Repositories;


use App\Entities\Payments\PaymentStatusEntity;
use App\Repositories\Interfaces\IPaymentRepository;
use Illuminate\Support\Facades\DB;

class PaymentRepository implements IPaymentRepository
{
    public function changeStatus(PaymentStatusEntity $entity): int
    {
        $id = $this->checkIfExists($entity->paymentId);
        if ($id) {
            DB::table('payments')
                ->where('payment_id', $entity->paymentId)
                ->update([
                    'status' => $entity->status,
                    'updated_at' => now(),
                ]);
        } else {
            $id = DB::table('payments')->insertGetId([
                'gateway' => $entity->gateway,
                'payment_id' => $entity->paymentId,
                'user_id' => 1,
                'status' => $entity->status,
                'amount' => $entity->amount,
                'amount_paid' => $entity->amountPaid,
                'additional' => json_encode($entity->additional),
                'created_at' => now(),
            ]);
        }

        return $id;
    }

    private function checkIfExists(int $paymentId): ?int
    {
        $payment = DB::table('payments')
            ->select('id')
            ->where('payment_id', $paymentId)
            ->first();

        return $payment?->id;
    }

    public function getTodayPaymentsCount(string $gateway): int
    {
        // Todo: "SELECT count(id) FROM payments WHERE gateway = %gateway% AND created_at %today%"
        return rand(30, 120);
    }
}
