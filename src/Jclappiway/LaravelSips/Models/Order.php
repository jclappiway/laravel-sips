<?php

namespace Jclappiway\LaravelSips\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

// use Jclappiway\LaravelSips\Observers\OrderObserver;

class Order extends Model
{

    protected $dates = ['created_at'];
    public function customer()
    {
        return $this->belongsTo('\Jclappiway\LaravelSips\Models\Customer');
    }

    public function transaction()
    {
        return $this->belongsTo('\Jclappiway\LaravelSips\Models\Transaction');
    }

    protected $fillable = [
        'amount',
    ];

    public static function boot()
    {
        parent::boot();
        $observer = \App::make('LaravelSipsOrderObserver');
        Order::observe(new $observer);
    }

    public function createTransaction(array $datas)
    {
        $datas['id'] = $datas['transaction_id'];
        $transaction = new Transaction($datas);
        $transaction->save();
        $this->transaction()->associate($transaction);
        $this->save();
    }

    public function generatePaymentForm()
    {
        $params                     = array();
        $params['merchant_id']      = config('sips.id');
        $params['merchant_country'] = config('sips.country');
        $params['currency_code']    = config('sips.currency_code');
        $params['pathfile']         = config('sips.pathfile');
        $params['amount']           = $this->amount;

        $params['normal_return_url']      = config('sips.normal_return_url');
        $params['cancel_return_url']      = config('sips.cancel_return_url');
        $params['automatic_response_url'] = config('sips.automatic_response_url');

        $params['language']            = config('sips.language');
        $params['payment_means']       = config('sips.payment_means');
        $params['header_flag']         = config('sips.header_flag');
        $params['capture_day']         = config('sips.capture_day');
        $params['capture_mode']        = config('sips.capture_mode');
        $params['bgcolor']             = config('sips.bgcolor');
        $params['block_align']         = config('sips.block_align');
        $params['block_order']         = config('sips.block_order');
        $params['textcolor']           = config('sips.textcolor');
        $params['receipt_complement']  = config('sips.receipt_complement');
        $params['caddie']              = config('sips.caddie');
        $params['customer_id']         = isset($this->customer->id) ? $this->customer->id : '';
        $params['customer_ip_address'] = isset($this->customer->ip_address) ? $this->customer->ip_address : '';
        $params['data']                = config('sips.data');
        $params['return_context']      = config('sips.return_context');
        $params['target']              = config('sips.target');
        $params['order_id']            = $this->id;
        $params['data']                = config('sips.style');

        $path_bin = config('sips.path_bin_request');

        $param_string = '';
        foreach ($params as $key => $value) {
            $param_string .= $key . '=' . $value . ' ';
        }

        $parm        = escapeshellcmd($param_string);
        $result      = exec("$path_bin $parm");
        $resultArray = explode("!", "$result");

        $code  = $resultArray[1];
        $error = $resultArray[2];
        $form  = $resultArray[3];

        if (($code == "") && ($error == "")) {
            throw new Exception("request bin not found", $path_bin);
        } else if ($code != 0) {
            throw new Exception("API request error", $error);
        } else {
            return $form;
        }
    }

    public static function generateResponse(Request $request)
    {

        // Request DATA contains crypted response
        $data    = $request->get('DATA');
        $message = "message=$data";

        $pathfile = config('sips.pathfile');
        $pathfile = "pathfile=$pathfile";
        $path_bin = config('sips.path_bin_response');

        $message = escapeshellcmd($message);
        $result  = exec("$path_bin $pathfile $message");
        $result  = explode("!", $result);

        $code     = $result[1];
        $error    = $result[2];
        $response = array();

        if (($code == "") && ($error == "")) {
            throw new Exception("executable response not found $path_bin");
        } else if ($code != 0) {
            throw new Exception("API call error $error");
        } else {
            $response['merchant_id']           = $result[3];
            $response['merchant_country']      = $result[4];
            $response['amount']                = $result[5];
            $response['transaction_id']        = $result[6];
            $response['payment_means']         = $result[7];
            $response['transmission_date']     = $result[8];
            $response['payment_time']          = $result[9];
            $response['payment_date']          = $result[10];
            $response['response_code']         = $result[11];
            $response['payment_certificate']   = $result[12];
            $response['authorisation_id']      = $result[13];
            $response['currency_code']         = $result[14];
            $response['card_number']           = $result[15];
            $response['cvv_flag']              = $result[16];
            $response['cvv_response_code']     = $result[17];
            $response['bank_response_code']    = $result[18];
            $response['complementary_code']    = $result[19];
            $response['complementary_info']    = $result[20];
            $response['return_context']        = $result[21];
            $response['caddie']                = $result[22];
            $response['receipt_complement']    = $result[23];
            $response['merchant_language']     = $result[24];
            $response['language']              = $result[25];
            $response['customer_id']           = $result[26];
            $response['order_id']              = $result[27];
            $response['customer_email']        = $result[28];
            $response['customer_ip_address']   = $result[29];
            $response['capture_day']           = $result[30];
            $response['capture_mode']          = $result[31];
            $response['data']                  = $result[32];
            $response['order_validity']        = $result[33];
            $response['transaction_condition'] = $result[34];
            $response['statement_reference']   = $result[35];
            $response['card_validity']         = $result[36];
            $response['score_value']           = $result[37];
            $response['score_color']           = $result[38];
            $response['score_info']            = $result[39];
            $response['score_threshold']       = $result[40];
            $response['score_profile']         = $result[41];
        }

        return $response;
    }
}
