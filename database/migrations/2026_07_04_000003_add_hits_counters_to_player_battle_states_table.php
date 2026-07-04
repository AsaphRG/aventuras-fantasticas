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
        Schema::table('player_battle_states', function (Blueprint $table) {
            $table->integer('enemy_hits_taken')->default(0)->after('enemy_current_energy');
            $table->integer('player_hits_taken')->default(0)->after('enemy_hits_taken');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_battle_states', function (Blueprint $table) {
            $table->dropColumn(['enemy_hits_taken', 'player_hits_taken']);
        });
    }
};
