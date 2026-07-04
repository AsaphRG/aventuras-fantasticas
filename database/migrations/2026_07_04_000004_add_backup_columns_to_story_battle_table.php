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
            $table->integer('turns_to_flee')->nullable()->after('enemy_id');
            $table->unsignedBigInteger('win_go_to')->nullable()->after('turns_to_flee');
            $table->unsignedBigInteger('flee_go_to')->nullable()->after('win_go_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('story_battle', function (Blueprint $table) {
            $table->dropColumn(['turns_to_flee', 'win_go_to', 'flee_go_to']);
        });
    }
};
