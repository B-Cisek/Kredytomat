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
            $table->decimal('margin', 4, 2);
            $table->decimal('commission', 4, 2);
            $table->string('type_of_installment');
            $table->foreignId('wibor_id')->constrained();
            $table->text('fixed_fees')->nullable();
            $table->text('changing_fees')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_simulations');
    }
};
