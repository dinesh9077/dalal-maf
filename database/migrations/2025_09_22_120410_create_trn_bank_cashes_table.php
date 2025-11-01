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
        Schema::create('trn_bank_cashes', function (Blueprint $table) {
            $table->id();
            $table->string('bank_id')->nullable();
            $table->string('module_id')->nullable();
            $table->string('transaction_type')->nullable()->index();
            $table->enum('transaction_mode', ['credit', 'debit']);
            $table->decimal('credit', 25, 4)->default(0);
            $table->decimal('debit', 25, 4)->default(0);
            $table->date('date')->nullable();
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('trn_bank_cashes');
    }
};
