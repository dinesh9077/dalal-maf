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
        Schema::create('prt_floors', function (Blueprint $table) {
          $table->id();
          $table->integer('user_id')->default(0);
          $table->string('enterprice_code')->nullable();
          $table->integer('property_id')->nullable()->index();
          $table->unsignedBigInteger('wing_id')->index();
          $table->string('floor_name')->nullable();
          $table->integer('floor_number');
          $table->integer('floors');
          $table->integer('status')->default(1);
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
        Schema::dropIfExists('prt_floors');
    }
};
