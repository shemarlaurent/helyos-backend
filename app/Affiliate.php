<?php

namespace App;

use App\Notifications\Affiliate\AffiliateResetPassword;
use App\Notifications\Affiliate\AffiliateVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Affiliate extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AffiliateResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new AffiliateVerifyEmail);
    }

    public function referrals()
    {
        return $this->morphMany(Referral::class, 'referable');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Referral::class, 'referable_id', 'id');
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

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
