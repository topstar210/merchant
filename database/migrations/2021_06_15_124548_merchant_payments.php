<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MerchantPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('merchant_payments_rev', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Merchant::class,'merchant_id');
            $table->foreignIdFor(\App\Models\User::class,'user_id');
            $table->double('amount');
            $table->double('charges')->default(0);
            $table->double('commission')->default(0);
            $table->double('exchange_amount');
            $table->double('exchange_rate');
            $table->string('base_currency');
            $table->string('exchange_currency');
            $table->string('account');
            $table->string('account_name')->nullable();
            $table->string('institution')->nullable();
            $table->string('service')->nullable();
            $table->string('product')->nullable();
            $table->string('response', 2000)->nullable();
            $table->enum('status', ['Pending','Success','Refund','Blocked','Failed'])->default('Pending');
            $table->foreignIdFor(\App\Models\Transaction::class,'transaction_id');
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
        //
        Schema::dropIfExists('merchant_payments_rev');
    }
}