<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Receipt
 * @package App
 * @property integer user_id
 * @property integer vendor_id
 * @property integer voucher_id
 * @property integer saving
 * @property integer amount
 * @property integer total
 * @property string reference
 * @property integer status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Receipt extends Model
{
    /**
     * @return string
     */
    public function getCameraReadyNumber($name)
    {
        $western_arabic = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $eastern_arabic = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        return str_replace($western_arabic, $eastern_arabic, number_format($this->{$name}));
    }
}
