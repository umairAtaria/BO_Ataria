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
            $table->string('Symbol');
            $table->string('Path');
            $table->string('Description');
            $table->float('Digits', 8, 5);
            $table->float('SwapMode', 8, 5);
            $table->float('SwapLong', 8, 5);
            $table->float('SwapShort', 8, 5);
            $table->integer('Swap3Day');
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
