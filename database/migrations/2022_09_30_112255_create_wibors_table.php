<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wibors', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('value', 4,2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wibors');
    }
};
