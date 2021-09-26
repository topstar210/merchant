<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantLiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_liens', function (Blueprint $table) {
            $table->id();
            $table->integer('merchant_id');
            $table->double('lien_amount')->default(0);
            $table->string('currency_id');
            $table->timestamp('lien_start_date')->nullable();
            $table->timestamp('lien_end_date')->nullable();
            $table->string('added_by')->nullable();
            $table->enum('status', ['LOCKED', 'RELEASED'])->default('LOCKED');
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
        Schema::dropIfExists('merchant_liens');
    }
}
