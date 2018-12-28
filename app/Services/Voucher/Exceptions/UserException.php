<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/16/18
 * Time: 4:36 PM
 */

namespace App\Services\Voucher\Exceptions;


class UserException extends VoucherException
{
    public function __construct()
    {
        $this->message = trans('voucher.user_whitelist', [], 'fa');
    }

}