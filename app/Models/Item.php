<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'player_id', // Importante para associar o item ao criar
        'name',
        'description',
        'abilityBonus',
        'category',
    ];

    /**
     * Get the player that owns the item.
     * Um 'Item' (item) pertence a um 'Player' (jogador).
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
