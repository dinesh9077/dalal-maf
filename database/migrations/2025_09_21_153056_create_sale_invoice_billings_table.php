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
        Schema::create('sale_invoice_billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('invoice_id')->index();
            $table->string('bank_id')->nullable();
            $table->string('invoice_type')->nullable();
            $table->integer('payment_type')->nullable()->comment('1 - Full Payment, 2-Partial Payment');
            $table->decimal('amount',25,4)->nullable();
            $table->date('payment_date')->nullable();
            $table->text('payment_method')->nullable();
            $table->longText('remarks')->nullable();
            $table->enum('status', [1,2])->default(2)->comment('1 - Paid, 2-Partial');
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
        Schema::dropIfExists('sale_invoice_billings');
    }
};
