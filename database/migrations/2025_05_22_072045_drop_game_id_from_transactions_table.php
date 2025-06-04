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
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus dulu foreign key constraint-nya
            $table->dropForeign(['game_id']);
            // Baru drop kolom game_id
            $table->dropColumn('game_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id')->nullable();

            // Tambahkan foreign key constraint lagi kalau rollback
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }
};
