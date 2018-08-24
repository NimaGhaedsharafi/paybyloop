<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 8/24/18
 * Time: 4:34 PM
 */

namespace App\Services\Wallet;

use App\Services\Wallet\Exception\InsufficientCredit;
use App\Wallet;


/**
 * Class WalletService
 * @package App\Services\Wallet
 */
class WalletService
{
    /**
     * Get balance
     * @param Payable $user
     * @return int
     */
    public function balance(Payable $user)
    {
        $lastTransaction = Wallet::where('user_id', $user->getId())
                ->where('user_type', $user->getType())
                ->latest('id')
                ->first();

        return $lastTransaction->balance ?? 0;
    }
    /**
     * Increase wallet credit
     * @param Payable $user
     * @param int $amount
     * @param $type
     * @param string $description
     * @return int
     */
    public function creditor(Payable $user, int $amount, $type, string $description)
    {
        $balance = $this->balance($user);
        $wallet = new Wallet();

        $wallet->user_id = $user->getId();
        $wallet->user_type = $user->getType();
        $wallet->creditor = $amount;
        $wallet->debtor = 0;
        $wallet->type = $type;
        $wallet->description = $description;
        $wallet->balance = $balance + $amount;
        $wallet->save();

        return $balance;
    }
    /**
     * Decrease wallet credit
     * @param Payable $user
     * @param int $amount
     * @param $type
     * @param string $description
     * @return int
     */
    public function debtor(Payable $user, int $amount, $type, string $description)
    {
        $balance = $this->balance($user);
        $wallet = new Wallet();

        // is it feasible to deduct this amount?
        if ($balance < $amount) {
            throw new InsufficientCredit();
        }

        $wallet->user_id = $user->getId();
        $wallet->user_type = $user->getType();
        $wallet->creditor = 0;
        $wallet->debtor = $amount;
        $wallet->type = $type;
        $wallet->description = $description;
        $wallet->balance = $balance - $amount;
        $wallet->save();

        return $balance;
    }
}