<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'forum_users');
    }
}
