<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/28/18
 * Time: 1:16 PM
 */

namespace App\Services\Gift\Exceptions;


class Overused extends GiftException
{

    /**
     * Overused constructor.
     */
    public function __construct()
    {
        $this->message = trans('voucher.gift.overused', [], 'fa');
    }
}