<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property User $user
 * @property int $id
 * @property int $user_id
 * @property int $user_type
 * @property int $receipt_id
 * @property int $balance
 * @property int $type
 * @property int $creditor
 * @property int $debtor
 * @property string $description
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read Receipt $receipt
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }
}
