<?php

namespace Jclappiway\LaravelSips\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Jclappiway\LaravelSips\Models\User;

class Payment extends BaseController
{
    public function postPayment(Request $request)
    {
        $inputs = $request->inputs();

        $user = User::create($inputs);

        $order = Order::create($inputs);

        dd($order);

    }
}
