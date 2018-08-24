<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property User $user
 * @property int $id
 * @property int $user_id
 * @property int $user_type
 * @property int $balance
 * @property int $type
 * @property int $creditor
 * @property int $debtor
 * @property string $description
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 */
class Wallet extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        if ($this->user_type == 1) {
            return $this->belongsTo(User::class);
        }
        return $this->belongsTo(Vendor::class);
    }
}
