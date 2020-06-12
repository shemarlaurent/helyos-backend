<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    protected $guarded = [];

//    protected array $with = ['product'];

    public function sales()
    {
        return $this->hasOne(SaleView::class);
    }

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    /** get the number of times a product variation wa sold
     * @return int
     */
    public function salesCount()
    {
        if ($this->sales) return $this->sales->sales;

        else return 0;
    }

    /**
     * record the sale of an item
     */
    public function decrementQuantity() : void
    {
        $quantity = intval($this->quantity);
        $quantity --;
        $this->quantity = $quantity;
        $this->save();

        if ($this->sales) {
            $this->sales->sales += 1;
            $this->sales->save();
        }

        else {
            $this->sales()->create(['sales' => 1]);
        }
    }
}
