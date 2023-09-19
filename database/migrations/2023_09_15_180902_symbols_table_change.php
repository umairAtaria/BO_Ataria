<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('symbols', function (Blueprint $table) {

            $table->string('Digits')->change();
            $table->string('SwapMode')->change();
            $table->string('SwapLong')->change();
            $table->string('SwapShort')->change();
            $table->string('Swap3Day')->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
