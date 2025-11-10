<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoryNode extends Model
{
    public $timestamps = false;

    public function choice(): HasMany
    {
        return $this->hasMany(Choice::class);
    }

    public function player(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function playerStoryNode(): HasMany
    {
        return $this->hasMany(PlayerStoryNode::class);
    }
}
