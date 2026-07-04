<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerBattleState extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'story_node_id',
        'enemy_id',
        'enemy_current_ability',
        'enemy_current_energy',
        'enemy_hits_taken',
        'player_hits_taken',
        'round_number',
        'status',
        'luck_test_context',
        'last_round_log'
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function storyNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'story_node_id');
    }

    public function enemy(): BelongsTo
    {
        return $this->belongsTo(Enemy::class);
    }
}
