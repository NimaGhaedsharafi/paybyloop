<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 8/25/18
 * Time: 12:23 AM
 */

namespace App\Exceptions;


class ApiException extends \RuntimeException
{
    protected $code;
    protected $message;
    protected $httpCode;

    /**
     * ApiException constructor.
     * @param $code
     * @param $message
     */
    public function __construct($code, $message, $httpCode = 400)
    {
        $this->code = $code;
        $this->message = $message;
        $this->httpCode = $httpCode;
    }
}