<?php

namespace Jclappiway\LaravelSips\Observers;

class TransactionObserver
{
    public function created($model)
    {
        $notifier = \App::make('LaravelSipsNotifier');
        $notifier->payment($model->id);
    }
}
