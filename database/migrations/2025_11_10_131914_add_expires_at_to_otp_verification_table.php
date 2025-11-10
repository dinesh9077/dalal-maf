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
        Schema::table('otp_verification', function (Blueprint $table) {
            if (!Schema::hasColumn('otp_verification', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('otp_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('otp_verification', function (Blueprint $table) {
            Schema::table('otp_verification', function (Blueprint $table) {
                $table->dropColumn('expires_at');
            });
        });
    }
};
