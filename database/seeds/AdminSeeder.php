<?php

use App\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => "John doe",
            'email' => 'johndoe@shophelyos.com',
            'password' => bcrypt('secret')
        ]);
    }
}
