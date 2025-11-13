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
        Schema::table('players', function (Blueprint $table) {
            $table->renameColumn('currentChapter', 'currentStoryNode');
            $table->unsignedBigInteger('currentStoryNode')->nullable()->change();
            $table->foreign('currentStoryNode')->references('id')->on('story_nodes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign(['currentStoryNode']);
            $table->integer('currentStoryNode')->nullable(false)->change();
            $table->renameColumn('currentStoryNode', 'currentChapter');
        });
    }
};
