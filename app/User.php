<?php

namespace App;

use App\Services\Wallet\Payable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property Wallet[] $wallet
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $cellphone
 * @property string $email
 * @property bool $cellphone_verified
 * @property bool $email_verified
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Authenticatable implements Payable, JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'cellphone', 'email', 'password', 'cellphone_verified', 'email_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token', 'created_at', 'updated_at', 'cellphone_verified', 'email_verified'
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->cellphone;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'cellphone';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
