<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payping extends Model
{
    const FailedVerification = -2;
    const FailedInitiation = -1;
    const Initiated = 0;
    const Requested = 1;
    const Verifying = 2;
    const Verified = 3;


    /**
     * @return string
     */
    public function getCameraReadyNumber()
    {
        $western_arabic = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $eastern_arabic = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        return str_replace($western_arabic, $eastern_arabic, number_format($this->amount));
    }
}
