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
        Schema::create('prt_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('property_id')->index();
            $table->text('category_id')->nullable();
            $table->text('unit_type')->nullable();
            $table->text('budget')->nullable();
            $table->decimal('sqft', 25, 4)->default(0);
            $table->decimal('price', 25, 4)->default(0);
            $table->unsignedInteger('furnishing_id')->default(0);
            $table->integer('no_of_bathroom')->default(0);
            $table->integer('no_of_balcony')->default(0);
            $table->integer('no_of_bedroom')->default(0);
            $table->integer('no_of_floor')->default(0);
            $table->string('property_age')->nullable();
            $table->string('facing')->nullable();
            $table->string('bachelor_allow')->nullable();
            $table->text('remark')->nullable();
            $table->string('property_status')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedInteger('wing_id')->default(0);
            $table->unsignedInteger('floor_id')->default(0);
            $table->string('flat_no')->nullable();
            $table->string('plot_size')->nullable();
            $table->integer('flat_number')->default(0);
            $table->unsignedInteger('property_work')->nullable();
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
        Schema::dropIfExists('prt_units');
    }
};
