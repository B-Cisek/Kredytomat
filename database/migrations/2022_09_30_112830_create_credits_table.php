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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->string('credit_name');
            $table->integer('amount_from');
            $table->integer('amount_to');
            $table->integer('period_from');
            $table->integer('period_to');
            $table->decimal('margin',4,2);
            $table->decimal('commission',4,2);
            $table->foreignId('bank_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wibor_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('credits');
    }
};
