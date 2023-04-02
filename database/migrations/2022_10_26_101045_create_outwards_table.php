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
        Schema::create('outwards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->default(24);
            $table->bigInteger('sr_id')->unsigned()->default(24);
            $table->integer('branch_id');
            $table->string('state_division');
            $table->string('sender_name');
            $table->string('sender_nrc_passport');
            $table->string('sender_address_ph');
            $table->string('purpose');
            $table->string('deposit_point');
            $table->string('remark_for_deposit_point');
            $table->string('receiver_name');
            $table->string('receiver_nrc_passport');
            $table->string('receiver_country_code');
            $table->integer('equivalent_usd');
            $table->integer('amount_mmk');
            $table->string('txd_date_time');
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
        Schema::dropIfExists('outwards');
    }
};
