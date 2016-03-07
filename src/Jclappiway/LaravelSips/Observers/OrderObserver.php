<?php

namespace Jclappiway\LaravelSips\Observers;

class OrderObserver
{

    public function __construct()
    {
    }

    public function creating($model)
    {
        $model->amount = $model->amount * 100;
    }

    public function saving($model)
    {
        $previous_status = $model->getOriginal('transaction_id');

        if ($previous_status == null && $model->transaction_id != null) {
            $notifier = \App::make('LaravelSipsNotifier');
            $notifier->payment($model->id);
        }
    }
}
