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
use Carbon\Carbon;


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
     * @param int $receiptId
     * @return int
     */
    public function creditor(Payable $user, int $amount, $type, string $description, $receiptId = 0)
    {
        $balance = $this->balance($user);

        $wallet = new Wallet();
        $wallet->user_id = $user->getId();
        $wallet->user_type = $user->getType();
        $wallet->receipt_id = $receiptId;
        $wallet->reference = 'LPC-' . str_pad(Carbon::now()->dayOfYear, 3, '0', STR_PAD_LEFT) . '-' . strtoupper(str_random(7));
        $wallet->creditor = $amount;
        $wallet->debtor = 0;
        $wallet->type = $type;
        $wallet->description = $description;
        $wallet->balance = $balance + $amount;
        $wallet->save();

        return $wallet->balance;
    }

    /**
     * Decrease wallet credit
     * @param Payable $user
     * @param int $amount
     * @param $type
     * @param string $description
     * @param int $receiptId
     * @return int
     */
    public function debtor(Payable $user, int $amount, $type, string $description, $receiptId = 0)
    {
        $balance = $this->balance($user);

        // is it feasible to deduct this amount?
        if ($balance < $amount) {
            throw new InsufficientCredit();
        }

        $wallet = new Wallet();
        $wallet->user_id = $user->getId();
        $wallet->user_type = $user->getType();
        $wallet->receipt_id = $receiptId;
        $wallet->reference = 'LPD-' . str_pad(Carbon::now()->dayOfYear, 3, '0', STR_PAD_LEFT) . '-' . strtoupper(str_random(7));
        $wallet->creditor = 0;
        $wallet->debtor = $amount;
        $wallet->type = $type;
        $wallet->description = $description;
        $wallet->balance = $balance - $amount;
        $wallet->save();

        return $wallet->balance;
    }
    
}
