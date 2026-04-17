<?php

namespace App\Models;

use Enchantment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlayerEnchantments extends Model
{
    protected $fillable = [
        'player_id',
        'enchantment_id',
        'used',
        'created_at',
        'updated_at'
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function enchantment(): BelongsTo
    {
        return $this->belongsTo(Enchantment::class, 'enchantment_id');
    }
}
