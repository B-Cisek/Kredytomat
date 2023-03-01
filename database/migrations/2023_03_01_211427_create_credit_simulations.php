<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_simulations', function (Blueprint $table) {
            $table->id();
            $table->integer('amount_of_credit');
            $table->integer('period');
            $table->integer('margin');
            $table->integer('commission');
            $table->integer('type_of_installment');
            $table->foreignId('wibor_id')->constrained();
            $table->text('fixedFees');
            $table->text('changingFees');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_simulations');
    }
};
