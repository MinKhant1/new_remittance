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
        Schema::create('total_inwards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('createddate');
            $table->integer('totaltrans');
            $table->integer('totalusd');
            $table->integer('totalmmkmillion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('total_inwards');
    }
};
