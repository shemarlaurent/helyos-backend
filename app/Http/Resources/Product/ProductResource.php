<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResource extends ResourceCollection
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
            'store_id' => $this->store_id,
            'brand' => $this->brand,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'sku' => $this->slug,
            'slug' => $this->slug,
            'variations' => $this->variations,
        ];
    }
}
