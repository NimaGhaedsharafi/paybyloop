<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 1/17/19
 * Time: 3:17 PM
 */

namespace App\Services;


class Utility
{
    /**
     * @param $text
     * @return mixed
     */
    public function en2fa($text)
    {
        $western_arabic = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $eastern_arabic = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        return str_replace($western_arabic, $eastern_arabic, $text);
    }

}