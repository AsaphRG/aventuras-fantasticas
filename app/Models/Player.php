<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'skillStart',
        'skillCurrent',
        'energyStart',
        'energyCurrent',
        'luckStart',
        'luckCurrent',
        'enchantmentStart',
        'gold',
        'currentStoryNode',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function enchantments(): HasMany
    {
        return $this->hasMany(Enchantment::class);
    }

    public function storyNode(): HasOne
    {
        return $this->hasOne(StoryNode::class);
    }

    public function flags(): HasMany
    {
        return $this->hasMany(PlayerFlag::class);
    }
}
