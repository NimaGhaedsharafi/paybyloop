<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 10/16/18
 * Time: 2:22 PM
 */

namespace App\Services\Gift\Events;


/**
 * Class VoucherRedeemed
 * @package App\Services\Voucher\Events
 */
class GiftUsed
{
    private $userId;
    private $voucherId;

    /**
     * VoucherRedeemed constructor.
     * @param $userId
     * @param $voucherId
     */
    public function __construct($userId, $voucherId)
    {
        $this->userId = $userId;
        $this->voucherId = $voucherId;
    }
}