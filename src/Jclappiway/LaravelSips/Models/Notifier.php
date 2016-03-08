<?php

namespace Jclappiway\LaravelSips\Models;

use Illuminate\Database\Eloquent\Model;
use Mail;

class Notifier extends Model
{

    public function payment(Order $order)
    {
        $user = $order->customer;

        Mail::send('jclappiway.laravel-sips::emails.payment', [
            'user' => $user, 'order' => $order,
        ], function ($m) use ($user, $order) {

            $m->from(
                config('sips.email_address_from'),
                config('sips.email_name_from')
            );

            $m->to(
                $user->email,
                $user->lastname . ' ' . $user->firstname
            )->subject(
                trans('jclappiway.laravel-sips::sips.mail_subject')
            );
        });
    }
}
