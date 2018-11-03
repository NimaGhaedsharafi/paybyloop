<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 10/30/18
 * Time: 8:20 PM
 */

namespace App\Enums;

/**
 * Class ErrorCode
 * @package App
 */
class ErrorCode
{
    const InvalidOTPToken = 1001;
    const InvalidAmount = 1002;
    const PaymentInitiationFailed = 1003;
    const PaymentVerificationFailed = 1004;
}