<?php

namespace Jclappiway\LaravelSips\Models;

use Illuminate\Database\Eloquent\Model;
use Mail;

class Notifier extends Model
{
    public function payment($order_id)
    {
        $order = Order::findOrFail($order_id);

        $user = $order->customer;

        Mail::send('jclappiway.laravel-sips::emails.payment', ['user' => $user, 'order' => $order], function ($m) use ($user, $order) {
            $m->from('hello@app.com', 'Your Application');

            $m->to($user->email, $user->lastname . ' ' . $user->firstname)->subject(trans('jclappiway.laravel-sips::sips.mail_subject'));
        });
    }
}
