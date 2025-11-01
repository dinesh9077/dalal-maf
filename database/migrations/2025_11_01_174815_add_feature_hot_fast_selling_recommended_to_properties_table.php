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
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('is_featured')->index()->default(false);
            $table->boolean('is_hot')->index()->default(false);
            $table->boolean('is_fast_selling')->index()->default(false);
            $table->boolean('is_recommended')->index()->default(false);
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'is_hot', 'is_fast_selling', 'is_recommended']);
        });
    }

};
