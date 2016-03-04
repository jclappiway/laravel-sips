<?php

namespace Jclappiway\LaravelSips\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Jclappiway\LaravelSips\Models\Customer;
use Jclappiway\LaravelSips\Models\Order;
use Validator;

class Payment extends BaseController
{
    public function __construct()
    {
        $this->datas = array();
    }

    public function payment(Request $request)
    {
        $inputs = $request->all();
        $order  = new Order($inputs);

        $validator = Validator::make($inputs, [
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|email|max:255',
            'address'   => 'required|max:255',
            'city'      => 'required|max:255',
            'zipcode'   => 'required|max:255',
            'country'   => 'required|max:255',
            'amount'    => 'required',
            'recurring' => 'required',
            'cgu'       => 'accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $inputs['ip_address'] = $request->getClientIp();
        $user                 = Customer::firstOrCreate(array('email' => $inputs['email']));
        $user->update($inputs);

        $order = new Order($inputs);
        $order->customer()->associate($user);
        if ($order->save()) {
            $this->datas['form'] = $order->generatePaymentForm();
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
            $order->save();
            $this->datas['order'] = $order;
            if ($order->transaction_id === null) {
                $order->createTransaction($datas);
                return view('jclappiway.laravel-sips::success', $this->datas);
            } else {
                return view('jclappiway.laravel-sips::success', $this->datas);
            }
        } catch (Exception $e) {
            dd($e);
            return view('jclappiway.laravel-sips::error', $this->datas);
        }
    }

    public function auto_response(Request $request)
    {
        //the user might not had clicked on return to store button
        //so let's do the transaction link if needed
        $datas = Order::generateResponse($request);

        $order                = Order::find($datas['order_id']);
        $this->datas['order'] = $order;
        if ($order->transaction_id === null) {
            $order->createTransaction($datas);
        }
    }
}
