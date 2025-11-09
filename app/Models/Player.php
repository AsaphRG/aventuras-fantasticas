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

    /**
     * Get the user that owns the player stats.
     * Um 'Player' (jogador) pertence a um 'User' (usuário).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the player.
     * Um 'Player' (jogador) pode ter muitos 'Items' (itens).
     */
    public function items(): HasMany
    {
        // Isso assume que você criará um model App\Models\Item
        return $this->hasMany(Item::class);
    }

    /**
     * Get the enchantments for the player.
     * Um 'Player' (jogador) pode ter muitos 'Enchantment' (encantamentos).
     */
    public function enchantments(): HasMany
    {
        // Isso assume que você criará um model App\Models\Enchantment
        return $this->hasMany(Enchantment::class);
    }

    public function storyNode(): HasOne
    {
        return $this->hasOne(StoryNode::class);
    }
}
