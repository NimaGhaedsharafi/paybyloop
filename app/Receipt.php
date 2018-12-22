<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
