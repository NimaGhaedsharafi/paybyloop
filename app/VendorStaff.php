<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VendorStaff
 * @package App
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int role
 * @property Vendor $vendor
 */
class VendorStaff extends Model
{
    use SoftDeletes;
    
    const Owner = 1;
    const Staff = 2;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * @return bool
     */
    public function isOwner()
    {
        return $this->role == self::Owner;
    }

    /**
     * @return bool
     */
    public function isStaff()
    {
        return $this->role == self::Staff;
    }
}
