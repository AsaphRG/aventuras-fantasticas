<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryBattle extends Model
{
    public function storyNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'story_node_id');
    }

    public function enemy(): BelongsTo
    {
        return $this->belongsTo(Enemy::class, 'enemy_id');
    }
}
