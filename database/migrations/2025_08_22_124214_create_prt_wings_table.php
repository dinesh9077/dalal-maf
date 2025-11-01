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
        Schema::create('prt_wings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('enterprice_code')->nullable();
            $table->unsignedBigInteger('property_id')->index();
            $table->string('wing_name')->nullable();
            $table->integer('wing_number');
            $table->integer('wings');
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
        Schema::dropIfExists('prt_wings');
    }
};
