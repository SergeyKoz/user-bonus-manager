<?php

namespace app\services;

use app\models\User;

interface TransferServiceInterface
{
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
    function transfer(User $from, User $to, float $sum, string $description): bool;
}