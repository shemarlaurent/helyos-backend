<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    protected $with = ['images'];



    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $hidden = [];
    protected $guarded = [];


    public function store() : BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function images() : HasMany
    {
        return $this->hasMany(Image::class, 'product_id', 'id');
    }

    /** record the sale of a product
     * @param string $size
     */
    public function sell(string $size)
    {
        $variation = $this->variations()
            ->where('name', strtoupper($size))
            ->first();

        $variation->decrementQuantity();
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function minVariation()
    {
        $variations = $this->variations;

        return  $variations->where('price', $variations->min('price'))->first();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function toggleLike($user, $value)
    {
        if(!$this->like) {
            $this->likes()->create([
                'user_id' => $user,
                'like' => $value,
            ]);
        }

        else {
            $this->likes->like = $value;
            $this->likes->save();
        }
    }

    public function toSearchableArray()
    {
        /**
         * Load the categories relation so that it's
         * available in the Laravel toArray() method
         */
        $this->images;
        $this->variations;

        $array = $this->toArray();

        $array = $this->transform($array);

        return $array;
    }

}
