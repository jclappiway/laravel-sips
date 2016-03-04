<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_means');
            $table->string('transmission_date');
            $table->string('payment_time');
            $table->string('payment_date');
            $table->string('response_code');
            $table->string('payment_certificate');
            $table->string('authorisation_id');
            $table->string('currency_code');
            $table->string('card_number');
            $table->string('cvv_flag');
            $table->string('cvv_response_code');
            $table->string('bank_response_code');
            $table->string('complementary_code');
            $table->string('complementary_inf');
            $table->string('return_context');
            $table->string('caddie');
            $table->string('receipt_complement');
            $table->string('merchant_language');
            $table->string('language');
            $table->string('capture_day');
            $table->string('capture_mode');
            $table->string('data');
            $table->string('order_validity');
            $table->string('transaction_condition');
            $table->string('statement_reference');
            $table->string('card_validity');
            $table->string('score_value');
            $table->string('score_color');
            $table->string('score_info');
            $table->string('score_threshold');
            $table->string('score_profile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
