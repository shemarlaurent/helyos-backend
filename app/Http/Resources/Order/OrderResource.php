<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Product\ProductVariation;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            //'variations' => new ProductVariation($this->collection->product_variation)
        ];
    }
}
