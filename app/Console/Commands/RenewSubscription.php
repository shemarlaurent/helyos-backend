<?php

namespace App\Console\Commands;

use App\Http\Controllers\User\PaymentController;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RenewSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew the subscription of users';

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
        $users = (new User)->subscribers();

        foreach ($users as $user) {
            if ($user->subscription->ends_at->diffInDays(Carbon::now()) <= 1 && $user->subscription->status) {

                if ((new PaymentController)->subscriptionCharge($user, $user->subscription->plan)) {
                    $user->subscription()->update([
                        'starts_at' => Carbon::now(),
                        'ends_at' => Carbon::now()->addMonths(1)
                    ]);
                }
            }
        }

        return true;
    }
}
