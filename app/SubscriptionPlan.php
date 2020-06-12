<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name'];

    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }
}
