<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants_rev', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('merchant_group_id')->unsigned()->index()->nullable();
            $table->foreign('merchant_group_id')->references('id')->on('merchant_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->string('mid', 13)->nullable()->comment("Unique ID for each Merchant");
            $table->string('merchant_name');
            $table->string('merchant_email');
            $table->string('merchant_address', 500);
            $table->string('merchant_phone');
            $table->string('country');
            $table->string('currency');
            $table->string('business_certificate', 1000)->nullable();
            $table->string('logo')->nullable();
            $table->string('site_url',100)->nullable();
            $table->double('commission')->default(0);
            $table->enum('status',['Active', 'Banned', 'Inactive'])->default('Active');

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
        Schema::dropIfExists('merchants_rev');
    }
}
