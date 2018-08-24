<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 8/24/18
 * Time: 9:24 PM
 */

namespace App\Services\Wallet\Exception;


/**
 * Class InsufficientCredit
 * @package App\Services\Wallet\Exception
 */
class InsufficientCredit extends \RuntimeException
{
    protected $message = 'credit is not sufficient';
}