<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    ];

    protected $with = ['subscription'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function card()
    {
        return $this->hasOne(Card::class);
    }

    public function referrer()
    {
        return $this->hasOne(Referral::class);
    }

    public function account()
    {
        return $this->morphOne(Account::class, 'account');
    }

    /** ubdate the account balance for a user
     * @param $amount
     */
    public function credit($amount)
    {
        $this->account->balance = $amount;

        $this->account->save();
    }

    public function orders() : MorphMany
    {
        return $this->morphMany(Order::class, 'orderable');
    }


    public function pricedProducts()
    {
        return $this->hasMany(PricedProduct::class);
    }

    public function forums()
    {
        return $this->belongsToMany(Forum::class, 'forum_users');
    }

    public function isSubscribed()
    {
        return $this->subscription()->where(function ($query) {
                $query->where('user_id', $this->id)
                    ->where('ends_at', '>', Carbon::now());
            })->first() !== null;
    }

    public function plan() : HasOneThrough
    {
        return $this->hasOneThrough(SubscriptionPlan::class, Subscription::class);
    }

    public function scopeMonthlyPricedProductCount(Builder $query)
    {
        $query->addSelect('priced_products_count')
            ->selectRaw('count(id)')
            ->from('priced_products')
            ->whereMonth('created_at', Carbon::now()->month)
            ->limit(1);

    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function premiumSubscribers()
    {
        return $this->whereHas('subscription', function ($query) {
                $query->where('plan_id', '>', 1);
                $query->where('ends_at', '>', Carbon::now());
            })->get();
    }

    public function subscribers()
    {
        return $this->with('subscription')->whereHas('subscription')->get();
    }

}
