<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Player;
use App\Models\StoryNode;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_story_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class, 'player_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(StoryNode::class, 'story_node_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_story_nodes');
    }
};
