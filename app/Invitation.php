<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $guarded = [];

    public static function validateToken(string $token) : bool
    {
        return Self::where('token', $token)->first() !== null;
    }
}
