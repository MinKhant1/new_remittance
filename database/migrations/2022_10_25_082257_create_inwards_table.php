<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inwards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->default(24);
            $table->bigInteger('sr_id')->unsigned()->default(24);
            $table->string('branch_id');
            $table->string('state_division');
            $table->string('receiver_name');
            $table->string('receiver_nrc_passport');
            $table->string('receiver_address_ph');
            $table->string('purpose');
            $table->string('withdraw_point');
            $table->string('remark_for_withdraw_point');
            $table->string('sender_name');
            $table->string('sender_country_code');
            $table->string('sender_nrc_passport');
            $table->string('currency_code');
            $table->integer('amount');
            $table->integer('equivalent_usd');
            $table->integer('amount_mmk');
            $table->dateTime('txd_date_time');
            $table->decimal("exchange_rate", 15,4);
            $table->decimal("exchange_rate_usd", 15,4);
            $table->integer("status");
            $table->longText("file");
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
        Schema::dropIfExists('inwards');
    }
};
