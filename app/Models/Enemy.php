<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Enemy extends Model
{
    public function storyNode(): BelongsToMany
    {
        return $this->belongsToMany(StoryNode::class, 'story_battle', 'enemy_id', 'story_node_id');
    }
}
