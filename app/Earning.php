<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Earning extends Model
{
    protected $guarded = [];

    public function earnable() : MorphTo
    {
        return $this->morphTo();
    }
}
