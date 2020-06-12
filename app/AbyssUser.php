<?php

namespace App;

use App\Notifications\AbyssUser\AbyssUserResetPassword;
use App\Notifications\AbyssUser\AbyssUserVerifyEmail;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AbyssUser extends Authenticatable implements JWTSubject
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
        'password', 'remember_token', 'card', 'card_expire', 'cvv2'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AbyssUserResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new AbyssUserVerifyEmail);
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

    public function forums()
    {
        return $this->belongsToMany(AbyssForum::class);
    }

    public function orders() : MorphMany
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    public function prizes()
    {
        return $this->hasMany(Winner::class);
    }

    public function claimedPrize(Winner $winner)
    {
        if ($winner->abyss_user_id === $this->id && $winner->claimed) return true;

        else false;
    }

    public function messages()
    {
        return $this->hasMany(AbyssForumMessage::class);
    }

    public function getForumMessages($forumId)
    {
        return $this->messages()->where('forum_id', $forumId)->get();

    }

    public function joinForum($forumId)
    {
        $this->forums()->sync($forumId);
    }

    public function isQualifiedForDraw()
    {
        return (bool) $this->address && $this->city && $this->country && $this->state && $this->zip_code;
    }
}
