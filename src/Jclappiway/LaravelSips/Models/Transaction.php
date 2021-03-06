<?php

namespace Jclappiway\LaravelSips\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    public function order()
    {
        return $this->hasOne('\Jclappiway\LaravelSips\Models\Order');
    }

    public static function boot()
    {
        $observer = App::make('LaravelSipsTransactionObserver');
        Transaction::observe(new $observer);
    }

    protected $fillable = [
        'id',
        'payment_means',
        'transmission_date',
        'payment_time',
        'payment_date',
        'response_code',
        'payment_certificate',
        'authorisation_id',
        'currency_code',
        'card_number',
        'cvv_flag',
        'cvv_response_code',
        'bank_response_code',
        'complementary_code',
        'complementary_inf',
        'return_context',
        'caddie',
        'receipt_complement',
        'merchant_language',
        'language',
        'capture_day',
        'capture_mode',
        'data',
        'order_validity',
        'transaction_condition',
        'statement_reference',
        'card_validity',
        'score_value',
        'score_color',
        'score_info',
        'score_threshold',
        'score_profile',
    ];
}
