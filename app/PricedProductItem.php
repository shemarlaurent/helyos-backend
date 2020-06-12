<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class PricedProductItem extends Model
{


    protected $guarded = [];

    public function product()
    {
        return $this->BelongsTo(Product::class);
    }

}
