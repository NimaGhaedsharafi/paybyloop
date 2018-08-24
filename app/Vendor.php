<?php

namespace App;

use App\Services\Wallet\Payable;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model implements Payable
{
    /**
     * @return integer
     */
    public function getType()
    {
        return 2;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
