<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->string('credit_name');
            $table->string('slug');
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

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
