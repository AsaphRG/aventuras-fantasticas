<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Player;
use App\Models\StoryNode;
use App\Models\Enemy;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_battle_states', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(StoryNode::class, 'story_node_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Enemy::class)->constrained()->onDelete('cascade');
            $table->integer('enemy_current_ability');
            $table->integer('enemy_current_energy');
            $table->integer('round_number')->default(1);
            $table->string('status')->default('in_progress'); // 'in_progress', 'waiting_luck_test', 'won', 'lost', 'fled'
            $table->string('luck_test_context')->default('none'); // 'none', 'player_hit', 'enemy_hit'
            $table->text('last_round_log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_battle_states');
    }
};
