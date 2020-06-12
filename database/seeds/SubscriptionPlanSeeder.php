<?php

use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /// subscription plans
        $plans = [
            [
                'name' => 'standard',
                'amount' => 10
            ],

            [
                'name' => 'premium',
                'amount' => 20
            ],

            [
                'name' =>  'pro',
                'amount' => 30
            ]
        ];
        foreach ($plans as $plan) {
            \App\SubscriptionPlan::create([
                'name' => $plan['name'],
                'amount' => $plan['amount']
            ]);
        }
    }
}
