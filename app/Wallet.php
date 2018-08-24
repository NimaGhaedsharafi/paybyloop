<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property User $user
 */
class Wallet extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
