<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbyssForum extends Model
{
    protected $guarded = [];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(AbyssUser::class);
    }

    public function messages()
    {
        return $this->hasMany(AbyssForumMessage::class);
    }

    public function winner()
    {
        return $this->hasOne(Winner::class);
    }

    public function getActiveForums()
    {
        return $this->with('users')->where('status', true)->get();
    }


    public function hasUsers()
    {
        return $this->users()->count();
    }

    public function assignWinner($user)
    {
        if (!$this->winner) {
            $this->winner()->create([
                'abyss_user_id' => $user->id,
            ]);
        }
    }

    public function close()
    {
        $this->status = false;

        $this->save();
    }
}
