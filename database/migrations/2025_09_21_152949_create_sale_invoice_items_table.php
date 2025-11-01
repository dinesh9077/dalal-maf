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
        Schema::create('sale_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->default(0);
            $table->unsignedBigInteger('invoice_id')->default(0)->index();
            $table->string('name')->nullable();
            $table->text('details')->nullable();
            $table->decimal('price',25,4)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('unit')->nullable();
            $table->decimal('discount',25,4)->nullable();
            $table->text('tax')->nullable();
            $table->string('hsn_sac')->nullable();
            $table->integer('include_tax')->nullable();
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
        Schema::dropIfExists('sale_invoice_items');
    }
};
