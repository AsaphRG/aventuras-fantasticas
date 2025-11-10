<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlayerStoryNode extends Model
{
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function storyNode(): HasOne
    {
        return $this->hasOne(StoryNode::class, 'story_node_id');
    }
}
