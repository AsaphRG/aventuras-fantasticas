<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerFlag extends Model
{
    use HasFactory;

    protected $fillable = [
        'flag_name'
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
