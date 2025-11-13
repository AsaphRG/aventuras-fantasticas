<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StoryNode;
use App\Models\Enemy;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('story_battle', function (Blueprint $table) {
            $table->foreignIdFor(StoryNode::class, 'story_node_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Enemy::class, 'enemy_id')->nullable()->constrained()->onDelete('cascade');
            $table->primary(['story_node_id', 'enemy_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_battle');
    }
};
