<?php

namespace app\services;

use app\models\Transaction;
use app\models\User;
use Yii;

class MoneyTransferService implements TransferServiceInterface
{
    const MIN_BALANCE = -1000;

    /**
     * Makes transfer operation between users
     *
     * @param User $from
     * @param User $to
     * @param float $sum
     * @param string $description
     * @return bool
     * @throws \Exception
     */
    public function transfer(User $from, User $to, float $sum, string $description): bool
    {
        if ($from->balance - $sum < MoneyTransferService::MIN_BALANCE) {
            throw new \Exception('Min balance reached');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $from->balance -= $sum;
            $from->save();

            $to->balance += $sum;
            $to->save();

            $transferTransaction = new Transaction();
            $transferTransaction->from_user_id = $from->id;
            $transferTransaction->to_user_id = $to->id;
            $transferTransaction->sum = $sum;
            $transferTransaction->description = $description;
            $transferTransaction->save();
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
        return true;
    }
}