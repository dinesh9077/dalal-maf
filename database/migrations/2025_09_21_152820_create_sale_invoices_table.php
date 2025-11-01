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
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->integer('customer_id')->nullable()->index();
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('sub_total',25,4)->default(0);
            $table->decimal('total_discount',25,4)->default(0);
            $table->decimal('grand_total',25,4)->default(0);
            $table->decimal('convert_total',25,4)->default(0);
            $table->time('time')->nullable();
            $table->string('prefix')->nullable();
            $table->longText('footer_note')->nullable();
            $table->text('tax_value')->nullable();
            $table->enum('billing_status',[1,2,3])->default(3);
            $table->integer('show_bank_id')->nullable();
            $table->string('recurring')->default(0);
            $table->string('repeat_type_custom')->nullable();
            $table->integer('repeat_every_custom')->default(0);
            $table->integer('cycle')->default(0);
            $table->integer('recurring_invoice')->default(0);
            $table->integer('recurring_reffrence')->default(0);
            $table->integer('recurring_stop')->default(0);
            $table->string('recurring_stop_datetime')->nullable();
            $table->string('receipt')->nullable();
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
        Schema::dropIfExists('sale_invoices');
    }
};
