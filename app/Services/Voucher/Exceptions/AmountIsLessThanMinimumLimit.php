<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/16/18
 * Time: 4:36 PM
 */

namespace App\Services\Voucher\Exceptions;


class AmountIsLessThanMinimumLimit extends VoucherException
{
    /**
     * AmountIsLessThanMinimumLimit constructor.
     * @param $min
     */
    public function __construct($min)
    {
        $this->message = trans('voucher.min', ['min' => $min], 'fa');
    }
}