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
        Schema::table('property_contacts', function (Blueprint $table) {
          $table->bigInteger('inquiry_by_user')->after('property_id')->nullable();
          $table->bigInteger('inquiry_by_vendor')->after('inquiry_by_user')->nullable();
          $table->string('comment')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_contacts', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
};
