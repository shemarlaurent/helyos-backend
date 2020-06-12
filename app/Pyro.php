<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pyro extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return HasOne
     */
    public function winner()
    {
        return $this->hasOne(PyroWinner::class);
    }

    public function activeEntry()
    {
        return $this->with('product.variations')->where('status', false)->first();
    }

    public function pickWinner($user)
    {
        if (!$this->winner) {
            $this->winner()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
