<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryBattle extends Model
{
    protected $table = 'story_battle';
    public $timestamps = false;

    protected $fillable = [
        'story_node_id',
        'enemy_id',
        'turns_to_flee',
        'win_go_to',
        'flee_go_to',
        'fight_mode',
        'fight_order',
        'can_flee',
        'flee_after_rounds',
        'flee_to_story_node_id',
        'flee_damage',
        'custom_damage_to_player',
        'custom_damage_to_enemy',
        'special_rules_json',
    ];

    protected $casts = [
        'can_flee' => 'boolean',
        'special_rules_json' => 'array',
    ];

    public function storyNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'story_node_id');
    }

    public function enemy(): BelongsTo
    {
        return $this->belongsTo(Enemy::class, 'enemy_id');
    }
}
