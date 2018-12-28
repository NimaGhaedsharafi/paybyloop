<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Carbon\Carbon $expires_in
 */
class Gift extends Model
{
    protected $dates = [
        'expires_in'
    ];

    /**
     * @return bool
     */
    public function isOverused()
    {
        return $this->used >= $this->max_use_time;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_in->isPast();
    }
}
