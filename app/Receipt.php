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
 * @property bool $has_voucher
 * @property-read Vendor vendor
 * @property-read User user
 * @property-read Voucher voucher
 */
class Receipt extends Model
{
    const Initiate = 0;
    const Done = 1;

    protected $appends = [
        'has_voucher'
    ];

    protected $hidden = [
        'id', 'user_id', 'vendor_id', 'voucher_id', 'created_at'
    ];

    /**
     * @return string
     */
    public function getCameraReadyNumber($name)
    {
        $western_arabic = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $eastern_arabic = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        return str_replace($western_arabic, $eastern_arabic, number_format($this->{$name}));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'used_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    /**
     * @return bool
     */
    public function getHasVoucherAttribute()
    {
        return $this->voucher_id != 0;
    }
}
