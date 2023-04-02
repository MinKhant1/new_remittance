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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->tinyInteger('type')->default(1);
            $table->boolean('inward')->nullable();
            $table->boolean('outward')->nullable();
            $table->boolean('total_inward')->nullable();
            $table->boolean('total_outward')->nullable();
            $table->boolean('total_inward_outward')->nullable();
            $table->boolean('inward_trans')->nullable();
            $table->boolean('outward_trans')->nullable();
            $table->boolean('inward_approve')->nullable();
            $table->boolean('outward_approve')->nullable();
            $table->boolean('company')->nullable();
            $table->boolean('branch')->nullable();
            $table->boolean('country')->nullable();
            $table->boolean('currency')->nullable();
            $table->boolean('purpose_of_trans')->nullable();
            $table->boolean('trans_max_limit')->nullable();
            $table->boolean('blacklist')->nullable();
            $table->boolean('exchange_rate')->nullable();
            $table->boolean('user_control')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
