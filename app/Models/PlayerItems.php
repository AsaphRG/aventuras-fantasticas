<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerItems extends Model
{
    use HasFactory;

    protected $table = 'player_items';

    protected $fillable = [
        'player_id',
        'name',
        'description',
        'abilityBonus',
        'category',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
