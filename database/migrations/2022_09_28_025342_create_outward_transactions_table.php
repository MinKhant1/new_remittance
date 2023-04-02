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
        Schema::create('outward_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('tran_no');
            $table->string('sender_name');
            $table->string('sender_nrcpassport');
            $table->string('sender_address');
            $table->string('sender_phno');
            $table->string('purpose_of_transaction');
            $table->integer('deposit_point');
            $table->string('receiver_name');
            $table->string('receiver_country');
            $table->integer('mmk_amount');
            $table->integer('equivalent_usd');
            $table->integer('thb_amount');
            $table->integer("approve_status");
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
        Schema::dropIfExists('outward_transactions');
    }
};
