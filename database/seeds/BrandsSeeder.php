<?php

use App\Brand;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = ['adidas', 'nike', 'puma', 'jordan', 'supreme', 'new balance', 'converse', 'vans', 'reebok', 'yeezy', 'air force'];

        foreach ($brands as $brand) {
            Brand::create(['name' => $brand]);
        }
    }
}
