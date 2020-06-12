<?php

namespace App\Console\Commands;

use App\NebulaPayment;
use Illuminate\Console\Command;

class ChargeNebulaOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nebula:pay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily billing of nebula orders';

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
        $nebulaOrders = NebulaPayment::with('order.orderable')->where('status', false)->get();

        foreach ($nebulaOrders as $nebulaOrder) {
            $payment = $nebulaOrder->chargeReminder($nebulaOrder->installments, $nebulaOrder->order->orderable);

            // update the payment information for the nebula order
            if ($payment) {
                $nebulaOrder->updateDetails($nebulaOrder->installments);
            } // cancel the nebula pay billing
            else {
                $nebulaOrder->cancel();
            }

        }
        return true;
    }
}
