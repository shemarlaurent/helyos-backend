<?php

namespace App;

use App\Notifications\User\OrderCanceled;
use App\Notifications\User\OrderFulfilled;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $guarded = [];

    public function orderable()
    {
        return $this->morphTo();
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nebula()
    {
        return $this->hasOne(NebulaPayment::class);
    }

    public function product_variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    /**
     * fulfill and order and notify user
     */
    public function fulfill()
    {
        $this->status = true;

        $this->save();

        $this->user->notify(new OrderFulfilled($this));
    }

    /**
     * cancel a users order
     */
    public function cancel()
    {
        $this->canceled = true;

        $this->save();

        $this->user->notify(new OrderCanceled($this));

    }


}
