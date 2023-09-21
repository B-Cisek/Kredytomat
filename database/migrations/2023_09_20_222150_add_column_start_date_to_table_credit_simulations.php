<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up(): void
    {
        Schema::table('credit_simulations', function (Blueprint $table) {
            $table->text('start_date')->nullable()->after('period');
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down(): void
    {
        Schema::table('credit_simulations', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });
    }
};
