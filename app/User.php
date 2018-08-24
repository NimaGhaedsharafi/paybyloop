<?php

namespace App;

use App\Services\Wallet\Payable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property Wallet[] $wallet
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $cellphone
 * @property string $email
 * @property bool $cellphone_verified
 * @property bool $email_verified
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Authenticatable implements Payable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'cellphone', 'email', 'password', 'cellphone_verified', 'email_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallet()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * @return integer
     */
    public function getType()
    {
        return 1;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
