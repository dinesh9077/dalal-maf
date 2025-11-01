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
        Schema::create('vendor_kycs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('aadhar_card_number')->nullable();
            $table->text('aadhar_front')->nullable();
            $table->text('aadhar_back')->nullable();
            $table->string('pancard_no')->nullable();
            $table->text('pancard')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->tinyInteger('is_gst')->nullable();
            $table->tinyInteger('is_aadhar')->nullable();
            $table->tinyInteger('is_pancard')->nullable();
            $table->text('admin_gst_note')->nullable();
            $table->text('admin_pancard_note')->nullable();
            $table->text('admin_document_note')->nullable();
            $table->text('admin_bank_note')->nullable();
            $table->tinyInteger('is_delete')->nullable();
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
        Schema::dropIfExists('vendor_kycs');
    }
};
