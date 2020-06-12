<?php

namespace App\Console\Commands;

use App\Http\Controllers\OrderController;
use App\Pryo;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PryoDraw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pryo:draw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'do a draw and pick a winner for the monthly pryo draw';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get all qualified users
        $users = (new User)->Subscribers();

        // get the active pryo entry for the month
        $pryo = (new Pryo)->activeEntry();


        /** pick a random winner */
        $winner = collect($users)->random(1)->first();

        /** assign a winner for the pryo challenge */
        $pryo->pickWinner($winner);

        /** fulfil the order for the winner */
        $winner->orders()->create([
            'store_id' => $pryo->product->store_id,
            'code' => Str::random(10),
            'product_variation_id' => $pryo->product->minVariation()->id,
            'amount' => 0,
            'type' => 'normal',
            'status' => true,
        ]);

        return true;
    }
}
