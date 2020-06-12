<?php

namespace App;

use App\Scope\ProductCount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ProductCount);
    }


    protected $guarded = [];

    public function seller() : BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productCount()
    {
        return $this->belongsTo(Product::class);
    }

    public function productsCount()
    {
        return $this->products()->count();
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }
}
