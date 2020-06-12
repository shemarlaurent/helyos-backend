<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $guarded = [];

    /*
     * Hidden fields
     */
    protected $hidden = [
        'card_number', 'card_expiration', 'card_cvv'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
