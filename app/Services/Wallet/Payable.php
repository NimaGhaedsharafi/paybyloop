<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 8/24/18
 * Time: 4:36 PM
 */

namespace App\Services\Wallet;


/**
 * Interface Payable
 * @package App\Services\Wallet
 */
interface Payable
{
    /**
     * @return integer
     */
    public function getType();

    /**
     * @return integer
     */
    public function getId();
}