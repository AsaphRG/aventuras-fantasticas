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
        Schema::table('story_battle', function (Blueprint $table) {
            $table->string('fight_mode')->default('single');
            $table->integer('fight_order')->default(1);
            $table->boolean('can_flee')->default(false);
            $table->integer('flee_after_rounds')->nullable();
            $table->unsignedBigInteger('flee_to_story_node_id')->nullable();
            $table->integer('flee_damage')->default(2);
            $table->integer('custom_damage_to_player')->default(2);
            $table->integer('custom_damage_to_enemy')->default(2);
            $table->json('special_rules_json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('story_battle', function (Blueprint $table) {
            $table->dropColumn([
                'fight_mode',
                'fight_order',
                'can_flee',
                'flee_after_rounds',
                'flee_to_story_node_id',
                'flee_damage',
                'custom_damage_to_player',
                'custom_damage_to_enemy',
                'special_rules_json'
            ]);
        });
    }
};
