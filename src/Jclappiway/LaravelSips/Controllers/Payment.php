<?php

namespace Jclappiway\LaravelSips\Controllers;

use App;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Jclappiway\LaravelSips\Models\Customer;
use Jclappiway\LaravelSips\Models\Order;

class Payment extends BaseController
{
    public function __construct()
    {
        $this->datas = array();
    }

    public function payment(Request $request)
    {
        $inputs   = $request->all();
        $order    = new Order($inputs);
        $customer = App::make('LaravelSipsCustomer');

        $valid = $customer::validate($inputs);

        if (!$valid) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $inputs['ip_address'] = $request->getClientIp();
        $user                 = $customer::firstOrCreate(array('email' => $inputs['email']));
        $user->update($inputs);

        $order = new Order($inputs);
        $order->customer()->associate($user);
        if ($order->save()) {
            $this->datas['form']   = $order->generatePaymentForm();
            $this->datas['amount'] = $order->amount;
            return view('jclappiway.laravel-sips::payment', $this->datas);
        }

        return true;
    }

    public function cancel(Request $request)
    {
        return view('jclappiway.laravel-sips::cancel', $this->datas);
    }

    public function success(Request $request)
    {
        try {
            $datas = Order::generateResponse($request);
            $order = Order::find($datas['order_id']);

            $this->datas['order'] = $order;

            return view('jclappiway.laravel-sips::success', $this->datas);
        } catch (Exception $e) {
            return view('jclappiway.laravel-sips::error', $this->datas);
        }
    }

    public function auto_response(Request $request)
    {
        //the user might not had clicked on return to store button
        //so let's do the transaction link if needed
        $datas = Order::generateResponse($request);

        if ($datas['response_code'] === "00") {
            $order = Order::find($datas['order_id']);

            $this->datas['order'] = $order;
            if ($order->transaction_id === null) {
                $order->createTransaction($datas);
            }
        }
    }
}
