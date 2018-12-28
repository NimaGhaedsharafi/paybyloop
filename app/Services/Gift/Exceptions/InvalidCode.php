<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/28/18
 * Time: 1:15 PM
 */

namespace App\Services\Gift\Exceptions;


class InvalidCode extends GiftException
{

    /**
     * InvalidCode constructor.
     */
    public function __construct()
    {
        $this->message = trans('voucher.gift.invalid', [], 'fa');
    }
}