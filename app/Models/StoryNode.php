<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Hashing\HashManager;

class StoryNode extends Model
{
    public $timestamps = false;

    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class, 'from_story_node_id');
    }

    public function player(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function playerStoryNode(): HasMany
    {
        return $this->hasMany(PlayerStoryNode::class);
    }

    public function storyBattle(): BelongsToMany
    {
        return $this->belongsToMany(Enemy::class, 'story_battle', 'story_node_id', 'enemy_id');
    }
}
