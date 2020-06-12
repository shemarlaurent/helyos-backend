<?php

namespace App;

use App\Http\Controllers\User\PaymentController;
use App\Notifications\User\OrderFulfilled;
use Illuminate\Database\Eloquent\Model;
use Margules\bplib\BluePay;

class NebulaPayment extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function chargeReminder($amount, $user)
    {
        return (new PaymentController)->chargeUser($amount, $user);
    }

    /** update the payment status of a nebula plan and full fill the order if payment is done
     * @param $amount
     */
    public function updateDetails($amount)
    {
        $this->paid = intval($this->paid) + intval($amount);
        $this->save();

        if ($this->paid >= $this->total) {

            $this->fulfillOrder();

        }
    }


    /**
     * handle order fulfilment
     */
    private function fulfillOrder()
    {
        // make the payment circle complete
        $this->status = true;
        $this->save();

        // fulfil the order
        $this->order->status = true;
        $this->order->save();

        $product = $this->order->product_variation->product;

        $product->sell($this->order->product_variation->name);

        $product->store->seller->credit($this->order->product_variation->price);

        // notify the user on order fulfilment
        $this->order->orderable->notify(new OrderFulfilled($this->order));
    }

    public function cancel()
    {
        $this->order->canceled = true;

        $this->order->save();
    }
}
