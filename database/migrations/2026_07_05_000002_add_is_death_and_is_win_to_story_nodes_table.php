<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('story_nodes', function (Blueprint $table) {
            $table->boolean('is_death')->default(false);
            $table->boolean('is_win')->default(false);
        });

        if (DB::table('story_nodes')->exists()) {
            DB::table('story_nodes')->where('id', 402)->update(['is_death' => true]);
            DB::table('story_nodes')->where('id', 400)->update(['is_win' => true]);
            // Marcar nós conhecidos que possuem como única escolha ir para 402 (mortes narrativas directas)
            DB::table('story_nodes')->whereIn('id', [82, 283])->update(['is_death' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('story_nodes', function (Blueprint $table) {
            $table->dropColumn(['is_death', 'is_win']);
        });
    }
};
