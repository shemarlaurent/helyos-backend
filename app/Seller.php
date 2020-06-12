<?php

namespace App;

use App\Notifications\Seller\SellerResetPassword;
use App\Notifications\Seller\SellerVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Seller extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $with = ['store'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SellerResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new SellerVerifyEmail);
    }

    public function store() : HasOne
    {
        return $this->hasOne(Store::class);
    }

    public function orders() : HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Store::class);
    }

    public function earnings()
    {
        return $this->morphOne(Earning::class, 'earnable');
    }

    public function credit($amount)
    {
        if ($this->earnings) {
            $this->earnings->balance += $amount;
            $this->earnings->total += $amount;

            $this->earnings->save();
        }

        else {
            $this->earnings()->create([
                'balance' => $amount,
                'total' => $amount
            ]);
        }

    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, Store::class);
    }

    public function scopeWithOrdersCount(Builder $query)
    {
        $query->addSelect('orders_count', function ($query) {
            $query->selectRaw('count(id)')
                ->from('orders')
                ->where('store_id', $this->store->id)
                ->limit(1);
        });
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
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
}
