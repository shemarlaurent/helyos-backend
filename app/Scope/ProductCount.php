<?php


namespace App\Scope;
use App\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ProductCount implements Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->addSelect([
            'product_count' => Product::selectRaw('count(id)')->where('store_id', 'store.id')->limit(1)
        ]);
    }
}
